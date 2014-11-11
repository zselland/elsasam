<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * WC_Shipstation_API_Export Class
 */
class WC_Shipstation_API_Export extends WC_Shipstation_API_Request {

	/**
	 * Do the request
	 */
	public function request() {
		global $wpdb;

		$this->validate_input( array( "start_date", "end_date" ) );

		header( 'Content-Type: text/xml' );
		$xml = new DOMDocument( '1.0', 'utf-8' );
		$xml->formatOutput = true;

		$page      = isset( $_GET['page'] ) ? absint( $_GET['page'] ) : 1;
		$exported  = 0;
		$tz_offset = get_option( 'gmt_offset' ) * 3600;
		$order_ids = $wpdb->get_col(
			$wpdb->prepare( "
					SELECT ID FROM {$wpdb->posts}
					WHERE post_type = 'shop_order'
					AND post_status IN ( '" . implode( "','", WC_ShipStation_Integration::$export_statuses ) . "' )
					AND %s <= post_date_gmt
					AND post_date_gmt <= %s
					LIMIT %d, %d
				",
				gmdate( "Y-m-d H:i:s", strtotime( $_GET['start_date'] ) ),
				gmdate( "Y-m-d H:i:s", strtotime( $_GET['end_date'] ) ),
				WC_SHIPSTATION_EXPORT_LIMIT * ( $page - 1 ),
				WC_SHIPSTATION_EXPORT_LIMIT
			)
		);

		$orders_xml = $xml->createElement( "Orders" );

		foreach ( $order_ids as $order_id ) {
			$order          = wc_get_order( $order_id );
			$needs_shipping = false;

			// Need to ship?
			foreach ( $order->get_items() as $item_id => $item ) {
				$product = $order->get_product_from_item( $item );

				if ( $product->needs_shipping() ) {
					$needs_shipping = true;
					break;
				}
			}

			if ( ! $needs_shipping ) {
				continue;
			}

			$order_xml = $xml->createElement( "Order" );

			$this->xml_append( $order_xml, 'OrderNumber', ltrim( $order->get_order_number(), '#' ) );
			$this->xml_append( $order_xml, 'OrderDate', gmdate( "m/d/Y H:i", strtotime( $order->order_date ) - $tz_offset ), false );
			$this->xml_append( $order_xml, 'OrderStatus', $order->get_status() );
			$this->xml_append( $order_xml, 'LastModified', gmdate( "m/d/Y H:i", strtotime( $order->modified_date ) - $tz_offset ), false );
			$this->xml_append( $order_xml, 'ShippingMethod', implode( ' | ', $this->get_shipping_methods( $order ) ) );

			$this->xml_append( $order_xml, 'OrderTotal', $order->get_total(), false );
			$this->xml_append( $order_xml, 'TaxAmount', $order->get_total_tax(), false );

			if ( class_exists( 'WC_COG' ) ) {
				$this->xml_append( $order_xml, 'CostOfGoods', wc_format_decimal( $order->wc_cog_order_total_cost ), false );
			}

			$this->xml_append( $order_xml, 'ShippingAmount', $order->get_total_shipping(), false );
			$this->xml_append( $order_xml, 'CustomerNotes', $order->customer_note );
			$this->xml_append( $order_xml, 'InternalNotes', implode( " | ", $this->get_order_notes( $order ) ) );

			// Custom fields - 1 is used for coupon codes
			$this->xml_append( $order_xml, 'CustomField1', implode( " | ", $order->get_used_coupons() ) );

			// Custom fields 2 and 3 can be mapped to a custom field via the following filters
			if ( $meta_key = apply_filters( 'woocommerce_shipstation_export_custom_field_2', '' ) ) {
				$this->xml_append( $order_xml, 'CustomField2', get_post_meta( $order_id, $meta_key, true ) );
			}

			if ( $meta_key = apply_filters( 'woocommerce_shipstation_export_custom_field_3', '' ) ) {
				$this->xml_append( $order_xml, 'CustomField3', get_post_meta( $order_id, $meta_key, true ) );
			}

			// Customer data
			$customer_xml = $xml->createElement( "Customer" );
			$this->xml_append( $customer_xml, 'CustomerCode', $order->billing_email );

  			$billto_xml = $xml->createElement( "BillTo" );
  			$this->xml_append( $billto_xml, 'Name', $order->billing_first_name . " " . $order->billing_last_name );
  			$this->xml_append( $billto_xml, 'Company', $order->billing_company );
  			$this->xml_append( $billto_xml, 'Phone', $order->billing_phone );
  			$this->xml_append( $billto_xml, 'Email', $order->billing_email );
  			$customer_xml->appendChild( $billto_xml );

  			$shipto_xml = $xml->createElement( "ShipTo" );

  			if ( empty( $order->shipping_country ) ) {
				$this->xml_append( $shipto_xml, 'Name', $order->billing_first_name . " " . $order->billing_last_name );
				$this->xml_append( $shipto_xml, 'Company', $order->billing_company );
				$this->xml_append( $shipto_xml, 'Address1', $order->billing_address_1 );
				$this->xml_append( $shipto_xml, 'Address2', $order->billing_address_2 );
				$this->xml_append( $shipto_xml, 'City', $order->billing_city );
				$this->xml_append( $shipto_xml, 'State', $order->billing_state );
				$this->xml_append( $shipto_xml, 'PostalCode', $order->billing_postcode );
				$this->xml_append( $shipto_xml, 'Country', $order->billing_country );
				$this->xml_append( $shipto_xml, 'Phone', $order->billing_phone );
  			} else {
				$this->xml_append( $shipto_xml, 'Name', $order->shipping_first_name . " " . $order->shipping_last_name );
				$this->xml_append( $shipto_xml, 'Company', $order->shipping_company );
				$this->xml_append( $shipto_xml, 'Address1', $order->shipping_address_1 );
				$this->xml_append( $shipto_xml, 'Address2', $order->shipping_address_2 );
				$this->xml_append( $shipto_xml, 'City', $order->shipping_city );
				$this->xml_append( $shipto_xml, 'State', $order->shipping_state );
				$this->xml_append( $shipto_xml, 'PostalCode', $order->shipping_postcode );
				$this->xml_append( $shipto_xml, 'Country', $order->shipping_country );
				$this->xml_append( $shipto_xml, 'Phone', $order->billing_phone );
	  		}
  			$customer_xml->appendChild( $shipto_xml );

  			$order_xml->appendChild( $customer_xml );

  			// Item data
  			$items_xml = $xml->createElement( "Items" );

  			foreach ( $order->get_items() as $item_id => $item ) {
				$product  = $order->get_product_from_item( $item );

				if ( ! $product->needs_shipping() ) {
					continue;
				}

				$item_xml = $xml->createElement( "Item" );
				$image_id = $product->get_image_id();

				if ( $image_id ) {
					$image_url = current( wp_get_attachment_image_src( $image_id, 'shop_thumbnail' ) );
				} else {
					$image_url = '';
				}

				$this->xml_append( $item_xml, 'LineItemID', $item_id );
  				$this->xml_append( $item_xml, 'SKU', $product->get_sku() );
  				$this->xml_append( $item_xml, 'Name', $product->get_title() );
  				$this->xml_append( $item_xml, 'ImageUrl', $image_url );
  				$this->xml_append( $item_xml, 'Weight', wc_get_weight( $product->get_weight(), 'oz' ), false );
  				$this->xml_append( $item_xml, 'WeightUnits', 'Ounces', false );
  				$this->xml_append( $item_xml, 'Quantity', $item['qty'], false );
  				$this->xml_append( $item_xml, 'UnitPrice', $order->get_item_total( $item, false, true ), false );

  				if ( $item['item_meta'] ) {
					$item_meta      = new WC_Order_Item_Meta( $item['item_meta'] );
					$formatted_meta = $item_meta->get_formatted( '_' );

					if ( ! empty( $formatted_meta ) ) {

						$options_xml = $xml->createElement( "Options" );

						foreach ( $formatted_meta as $meta_key => $meta ) {
							$option_xml  = $xml->createElement( "Option" );
							$this->xml_append( $option_xml, 'Name', $meta['label'] );
							$this->xml_append( $option_xml, 'Value', $meta['value'] );
							$options_xml->appendChild( $option_xml );
						}

						$item_xml->appendChild( $options_xml );
					}
				}

				$items_xml->appendChild( $item_xml );
  			}

  			// Append cart level discount line
  			if ( $order->get_order_discount() ) {
	  			$item_xml  = $xml->createElement( "Item" );
				$this->xml_append( $item_xml, 'SKU', "order-discount" );
				$this->xml_append( $item_xml, 'Name', __( 'Order Discount', 'woocommerce-shipstation' ) );
				$this->xml_append( $item_xml, 'Adjustment', "true", false );
				$this->xml_append( $item_xml, 'Quantity', 1, false );
				$this->xml_append( $item_xml, 'UnitPrice', $order->get_order_discount(), false );
				$items_xml->appendChild( $item_xml );
			}

			// Append items XML
  			$order_xml->appendChild( $items_xml );
  			$orders_xml->appendChild( $order_xml );

			$exported ++;
		}

		$orders_xml->setAttribute( "page", $page );
		$xml->appendChild( $orders_xml );
		echo $xml->saveXML();

		$this->log( sprintf( __( "Exported %s orders", 'woocommerce-shipstation' ), $exported ) );
	}

	/**
	 * Get shipping method names
	 * @param  WC_Order $order
	 * @return array
	 */
	private function get_shipping_methods( $order ) {
		$shipping_methods = $order->get_shipping_methods();
		$shipping_method_names = array();

		foreach ( $shipping_methods as $shipping_method ) {
			$shipping_method_names[] = $shipping_method['name'];
		}

		return $shipping_method_names;
	}

	/**
	 * Get Order Notes
	 * @param  WC_Order $order
	 * @return array
	 */
	private function get_order_notes( $order ) {
		$args = array(
			'post_id' => $order->id,
			'approve' => 'approve',
			'type'    => 'order_note'
		);

		remove_filter( 'comments_clauses', array( 'WC_Comments', 'exclude_order_comments' ), 10, 1 );

		$notes = get_comments( $args );

		add_filter( 'comments_clauses', array( 'WC_Comments', 'exclude_order_comments' ), 10, 1 );

		$order_notes = array();

		foreach ( $notes as $note ) {
			$order_notes[] = $note->comment_content;
		}

		return $order_notes;
	}

	/**
	 * Append XML as cdata
	 */
	private function xml_append( $append_to, $name, $value, $cdata = true ) {
		$data = $append_to->appendChild( $append_to->ownerDocument->createElement( $name ) );
		if ( $cdata ) {
			$data->appendChild( $append_to->ownerDocument->createCDATASection( $value ) );
		} else {
			$data->appendChild( $append_to->ownerDocument->createTextNode( $value ) );
		}
	}
}

return new WC_Shipstation_API_Export();