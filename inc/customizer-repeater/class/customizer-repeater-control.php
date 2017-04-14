<?php
/**
 * General repeater class
 *
 * @package azera-shop
 */

if ( ! class_exists( 'WP_Customize_Control' ) ) {
	return null;
}

/**
 * Class Azera_Shop_General_Repeater
 */
class Azera_Shop_General_Repeater extends WP_Customize_Control {

	/**
	 * Id
	 *
	 * @var integer $id id
	 */
	public $id;


	/**
	 * Box title
	 *
	 * @var string $boxtitle Box title
	 */
	private $boxtitle = array();

	/**
	 * Add field label
	 *
	 * @var array $add_field_label Field lavel
	 */
	private $add_field_label = array();

	/**
	 * Control for image
	 *
	 * @var bool $azera_shop_image_control Control for image
	 */
	private $azera_shop_image_control = false;

	/**
	 * Control for icon
	 *
	 * @var bool $azera_shop_icon_control Control for icon
	 */
	private $azera_shop_icon_control = false;

	/**
	 * Control for title
	 *
	 * @var bool $azera_shop_title_control Control for title
	 */
	private $azera_shop_title_control = false;

	/**
	 * Control for subtitle
	 *
	 * @var bool $azera_shop_subtitle_control Control for subtitle
	 */
	private $azera_shop_subtitle_control = false;

	/**
	 * Control for text
	 *
	 * @var bool $azera_shop_text_control Control for text
	 */
	private $azera_shop_text_control = false;

	/**
	 * Control for link
	 *
	 * @var bool $azera_shop_link_control Control for link
	 */
	private $azera_shop_link_control = false;

	/**
	 * Control for shortcode
	 *
	 * @var bool $azera_shop_shortcode_control Control for shortcode
	 */
	private $azera_shop_shortcode_control = false;

	/**
	 * Control for repeater
	 *
	 * @var bool $azera_shop_socials_repeater_control Control for repeater
	 */
	private $azera_shop_socials_repeater_control = false;

	/**
	 * Icon container.
	 *
	 * @var string $azera_shop_icon_container Icon container.
	 */
	private $azera_shop_icon_container = '';

	/**
	 * Class constructor
	 *
	 * @param string  $manager Manager.
	 * @param integer $id Id.
	 * @param array   $args Array of parameters.
	 */
	public function __construct( $manager, $id, $args = array() ) {
		parent::__construct( $manager, $id, $args );
		/*Get options from customizer.php*/
		$this->add_field_label = esc_html__( 'Add new field', 'azera-shop' );
		if ( ! empty( $args['add_field_label'] ) ) {
			$this->add_field_label = $args['add_field_label'];
		}

		$this->boxtitle   = esc_html__( 'Cusomizer Repeater','azera-shop' );
		if ( ! empty( $args['item_name'] ) ) {
			$this->boxtitle = $args['item_name'];
		} elseif ( ! empty( $this->label ) ) {
			$this->boxtitle = $this->label;
		}

		if ( ! empty( $args['azera_shop_image_control'] ) ) {
			$this->azera_shop_image_control = $args['azera_shop_image_control'];
		}

		if ( ! empty( $args['azera_shop_icon_control'] ) ) {
			$this->azera_shop_icon_control = $args['azera_shop_icon_control'];
		}

		if ( ! empty( $args['azera_shop_title_control'] ) ) {
			$this->azera_shop_title_control = $args['azera_shop_title_control'];
		}

		if ( ! empty( $args['azera_shop_subtitle_control'] ) ) {
			$this->azera_shop_subtitle_control = $args['azera_shop_subtitle_control'];
		}

		if ( ! empty( $args['azera_shop_text_control'] ) ) {
			$this->azera_shop_text_control = $args['azera_shop_text_control'];
		}

		if ( ! empty( $args['azera_shop_link_control'] ) ) {
			$this->azera_shop_link_control = $args['azera_shop_link_control'];
		}

		if ( ! empty( $args['azera_shop_shortcode_control'] ) ) {
			$this->azera_shop_shortcode_control = $args['azera_shop_shortcode_control'];
		}

		if ( ! empty( $args['azera_shop_socials_repeater_control'] ) ) {
			$this->azera_shop_socials_repeater_control = $args['azera_shop_socials_repeater_control'];
		}

		if ( ! empty( $id ) ) {
			$this->id = $id;
		}

		if ( file_exists( get_template_directory() . '/inc/customizer-repeater/inc/icons.php' ) ) {
			$this->azera_shop_icon_container = 'inc/customizer-repeater/inc/icons';
		}
	}

	/**
	 * Enqueue scripts
	 */
	public function enqueue() {

		wp_enqueue_style( 'azera-shop-font-awesome', azera_shop_get_file( '/css/font-awesome.min.css' ),'4.7' );

		wp_enqueue_style( 'azera-shop-admin-stylesheet', azera_shop_get_file( '/inc/customizer-repeater/css/admin-style.css' ),'1.0.0' );

		wp_enqueue_script( 'azera-shop-script', azera_shop_get_file( '/inc/customizer-repeater/js/customizer_repeater.js' ), array( 'jquery', 'jquery-ui-draggable' ), '1.0.1', true );

		wp_enqueue_script( 'azera-shop-fontawesome-iconpicker', azera_shop_get_file( '/inc/customizer-repeater/js/fontawesome-iconpicker.min.js' ), array( 'jquery' ), '1.0.0', true );

		wp_enqueue_style( 'azera-shop-fontawesome-iconpicker-script', azera_shop_get_file( '/inc/customizer-repeater/css/fontawesome-iconpicker.min.css' ) );
	}

	/**
	 * Render function
	 */
	public function render_content() {

		/*Get values (json format)*/
		$values = $this->value();

		/*Get default options*/
		$this_default = ! empty( $this->value() ) ? json_decode( $values ) : json_decode( $this->setting->default );

		/*Decode values*/
		$json = json_decode( $values );

		if ( ! is_array( $json ) ) {
			$json = array( $values );
		} ?>

		<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
		<div class="customizer-repeater-general-control-repeater customizer-repeater-general-control-droppable">
			<?php
			if ( ! azera_shop_general_repeater_is_empty( $values ) ) {
				if ( ! empty( $this_default ) ) {
					$this->iterate_array( $this_default ); ?>
					<input type="hidden"
					       id="customizer-repeater-<?php echo esc_attr( $this->id ); ?>-colector" <?php $this->link(); ?>
					       class="customizer-repeater-colector"
					       value="<?php echo esc_textarea( json_encode( $this_default ) ); ?>"/>
					<?php
				} else {
					$this->iterate_array(); ?>
					<input type="hidden"
					       id="customizer-repeater-<?php echo esc_attr( $this->id ); ?>-colector" <?php $this->link(); ?>
					       class="customizer-repeater-colector"/>
					<?php
				}
			} else {
				$this->iterate_array( $json ); ?>
				<input type="hidden" id="customizer-repeater-<?php echo esc_attr( $this->id ); ?>-colector" <?php $this->link(); ?>
				       class="customizer-repeater-colector" value="<?php echo esc_textarea( $this->value() ); ?>"/>
				<?php
			} ?>
			</div>
		<button type="button" class="button add_field customizer-repeater-new-field">
			<?php echo esc_html( $this->add_field_label ); ?>
		</button>
		<?php
	}

	/**
	 * Iterate through array to display boxes/
	 *
	 * @param array $array Control input.
	 */
	private function iterate_array( $array = array() ) {
		/*Counter that helps checking if the box is first and should have the delete button disabled*/
		$it = 0;
		if ( ! empty( $array ) ) {
			foreach ( $array as $icon ) { ?>
				<div class="customizer-repeater-general-control-repeater-container customizer-repeater-draggable">
					<div class="customizer-repeater-customize-control-title">
						<?php echo esc_html( $this->boxtitle ); ?>
					</div>
					<div class="customizer-repeater-box-content-hidden">
						<?php
						$choice = '';
						$image_url = '';
						$icon_value = '';
						$title = '';
						$subtitle = '';
						$text = '';
						$link = '';
						$shortcode = '';
						$repeater = '';

						if ( ! empty( $icon->id ) ) {
							$id = $icon->id;
						}
						if ( ! empty( $icon->choice ) ) {
							$choice = $icon->choice;
						}
						if ( ! empty( $icon->image_url ) ) {
							$image_url = $icon->image_url;
						}
						if ( ! empty( $icon->icon_value ) ) {
							$icon_value = $icon->icon_value;
						}
						if ( ! empty( $icon->title ) ) {
							$title = $icon->title;
						}
						if ( ! empty( $icon->subtitle ) ) {
							$subtitle = $icon->subtitle;
						}
						if ( ! empty( $icon->text ) ) {
							$text = $icon->text;
						}
						if ( ! empty( $icon->link ) ) {
							$link = $icon->link;
						}
						if ( ! empty( $icon->shortcode ) ) {
							$shortcode = $icon->shortcode;
						}

						if ( ! empty( $icon->social_repeater ) ) {
							$repeater = $icon->social_repeater;
						}

						if ( $this->azera_shop_image_control == true && $this->azera_shop_icon_control == true ) {
							$this->icon_type_choice( $choice );
						}
						if ( $this->azera_shop_image_control == true ) {
							$this->image_control( $image_url, $choice );
						}
						if ( $this->azera_shop_icon_control == true ) {
							$this->icon_picker_control( $icon_value, $choice );
						}
						if ( $this->azera_shop_title_control == true ) {
							$this->input_control(array(
								'label' => apply_filters( 'repeater_input_labels_filter', esc_html__( 'Title','azera-shop' ), $this->id, 'azera_shop_title_control' ),
								'class' => 'customizer-repeater-title-control',
								'type'  => apply_filters( 'repeater_input_types_filter', '', $this->id, 'azera_shop_title_control' ),
							), $title);
						}
						if ( $this->azera_shop_subtitle_control == true ) {
							$this->input_control(array(
								'label' => apply_filters( 'repeater_input_labels_filter', esc_html__( 'Subtitle','azera-shop' ), $this->id, 'azera_shop_subtitle_control' ),
								'class' => 'customizer-repeater-subtitle-control',
								'type'  => apply_filters( 'repeater_input_types_filter', '', $this->id, 'azera_shop_subtitle_control' ),
							), $subtitle);
						}
						if ( $this->azera_shop_text_control == true ) {
							$this->input_control(array(
								'label' => apply_filters( 'repeater_input_labels_filter', esc_html__( 'Text','azera-shop' ), $this->id, 'azera_shop_text_control' ),
								'class' => 'customizer-repeater-text-control',
								'type'  => apply_filters( 'repeater_input_types_filter', 'textarea', $this->id, 'azera_shop_text_control' ),
							), $text);
						}
						if ( $this->azera_shop_link_control ) {
							$this->input_control(array(
								'label' => apply_filters( 'repeater_input_labels_filter', esc_html__( 'Link','azera-shop' ), $this->id, 'azera_shop_link_control' ),
								'class' => 'customizer-repeater-link-control',
								'sanitize_callback' => 'esc_url_raw',
								'type'  => apply_filters( 'repeater_input_types_filter', '', $this->id, 'azera_shop_link_control' ),
							), $link);
						}
						if ( $this->azera_shop_shortcode_control == true ) {
							$this->input_control(array(
								'label' => apply_filters( 'repeater_input_labels_filter', esc_html__( 'Shortcode','azera-shop' ), $this->id, 'azera_shop_shortcode_control' ),
								'class' => 'customizer-repeater-shortcode-control',
								'type'  => apply_filters( 'repeater_input_types_filter', '', $this->id, 'azera_shop_shortcode_control' ),
							), $shortcode);
						}
						if ( $this->azera_shop_socials_repeater_control == true ) {
							$this->repeater_control( $repeater );
						} ?>

						<input type="hidden" class="social-repeater-box-id" value="<?php if ( ! empty( $id ) ) {
							echo esc_attr( $id );
} ?>">
						<button type="button" class="social-repeater-general-control-remove-field" <?php if ( $it == 0 ) {
							echo 'style="display:none;"';
} ?>>
							<?php esc_html_e( 'Delete field', 'azera-shop' ); ?>
						</button>

					</div>
				</div>

				<?php
				$it++;
			}// End foreach().
		} else { ?>
			<div class="customizer-repeater-general-control-repeater-container">
				<div class="customizer-repeater-customize-control-title">
					<?php echo esc_html( $this->boxtitle ); ?>
				</div>
				<div class="customizer-repeater-box-content-hidden">
					<?php
					if ( $this->azera_shop_image_control == true && $this->azera_shop_icon_control == true ) {
						$this->icon_type_choice();
					}
					if ( $this->azera_shop_image_control == true ) {
						$this->image_control();
					}
					if ( $this->azera_shop_icon_control == true ) {
						$this->icon_picker_control();
					}
					if ( $this->azera_shop_title_control == true ) {
						$this->input_control( array(
							'label' => apply_filters( 'repeater_input_labels_filter', esc_html__( 'Title','azera-shop' ), $this->id, 'azera_shop_title_control' ),
							'class' => 'customizer-repeater-title-control',
							'type'  => apply_filters( 'repeater_input_types_filter', '', $this->id, 'azera_shop_title_control' ),
						) );
					}
					if ( $this->azera_shop_subtitle_control == true ) {
						$this->input_control( array(
							'label' => apply_filters( 'repeater_input_labels_filter', esc_html__( 'Subtitle','azera-shop' ), $this->id, 'azera_shop_subtitle_control' ),
							'class' => 'customizer-repeater-subtitle-control',
							'type'  => apply_filters( 'repeater_input_types_filter', '', $this->id, 'azera_shop_subtitle_control' ),
						) );
					}
					if ( $this->azera_shop_text_control == true ) {
						$this->input_control( array(
							'label' => apply_filters( 'repeater_input_labels_filter', esc_html__( 'Text','azera-shop' ), $this->id, 'azera_shop_text_control' ),
							'class' => 'customizer-repeater-text-control',
							'type'  => apply_filters( 'repeater_input_types_filter', 'textarea', $this->id, 'azera_shop_text_control' ),
						) );
					}
					if ( $this->azera_shop_link_control == true ) {
						$this->input_control( array(
							'label' => apply_filters( 'repeater_input_labels_filter', esc_html__( 'Link','azera-shop' ), $this->id, 'azera_shop_link_control' ),
							'class' => 'customizer-repeater-link-control',
							'type'  => apply_filters( 'repeater_input_types_filter', '', $this->id, 'azera_shop_link_control' ),
						) );
					}
					if ( $this->azera_shop_shortcode_control == true ) {
						$this->input_control( array(
							'label' => apply_filters( 'repeater_input_labels_filter', esc_html__( 'Shortcode','azera-shop' ), $this->id, 'azera_shop_shortcode_control' ),
							'class' => 'customizer-repeater-shortcode-control',
							'type'  => apply_filters( 'repeater_input_types_filter', '', $this->id, 'azera_shop_shortcode_control' ),
						) );
					}
					if ( $this->azera_shop_socials_repeater_control == true ) {
						$this->repeater_control();
					} ?>
					<input type="hidden" class="social-repeater-box-id">
					<button type="button" class="social-repeater-general-control-remove-field button" style="display:none;">
						<?php esc_html_e( 'Delete field', 'azera-shop' ); ?>
					</button>
				</div>
			</div>
			<?php
		}// End if().
	}

	/**
	 * Function to display inputs
	 *
	 * @param array  $options Input options.
	 * @param string $value Input value.
	 */
	private function input_control( $options, $value = '' ) {
	?>
		<span class="customize-control-title"><?php echo esc_html( $options['label'] ); ?></span>
		<?php
		if ( ! empty( $options['type'] ) ) {
			switch ( $options['type'] ) {
				case 'textarea':?>
					<textarea class="<?php echo esc_attr( $options['class'] ); ?>" placeholder="<?php echo esc_attr( $options['label'] ); ?>">
						<?php echo ( ! empty( $options['sanitize_callback'] ) ?  call_user_func_array( $options['sanitize_callback'], array( $value ) ) : esc_attr( $value ) ); ?>
					</textarea>
					<?php
					break;
			}
		} else { ?>
			<input type="text" value="<?php echo ( ! empty( $options['sanitize_callback'] ) ?  call_user_func_array( $options['sanitize_callback'], array( $value ) ) : esc_attr( $value ) ); ?>" class="<?php echo esc_attr( $options['class'] ); ?>" placeholder="<?php echo esc_attr( $options['label'] ); ?>"/>
			<?php
		}
	}

	/**
	 * Function to display iconpicker.
	 *
	 * @param string $value Input value.
	 * @param string $show Show or hide this input.
	 */
	private function icon_picker_control( $value = '', $show = '' ) {
	?>
		<div class="social-repeater-general-control-icon" <?php if ( $show === 'azera_shop_image' || $show === 'azera_shop_none' ) { echo 'style="display:none;"'; } ?>>
			<span class="customize-control-title">
				<?php esc_html_e( 'Icon','azera-shop' ); ?>
			</span>
			<span class="description customize-control-description">
				<?php
				/* translators: %s is link to FontAwesome */
				printf( esc_html__( 'Note: Some icons may not be displayed here. You can see the full list of icons at %s', 'azera-shop' ),
	                /* translators: %s is link label*/
					sprintf( '<a href="http://fontawesome.io/icons/" rel="nofollow">%s</a>',
						esc_html__( 'FontAwesome', 'azera-shop' )
					)
				); ?>
			</span>
			<div class="input-group icp-container">
				<input data-placement="bottomRight" class="icp icp-auto" value="<?php if ( ! empty( $value ) ) { echo esc_attr( $value );} ?>" type="text">
				<span class="input-group-addon">
					<i class="fa <?php echo esc_attr( $value ); ?>"></i>
				</span>
			</div>
			<?php get_template_part( $this->azera_shop_icon_container ); ?>
		</div>
		<?php
	}

	/**
	 * Input control for images
	 *
	 * @param string $value Input value.
	 * @param string $show Display image control.
	 */
	private function image_control( $value = '', $show = '' ) {
	?>
		<div class="customizer-repeater-image-control" <?php if ( $show === 'azera_shop_icon' || $show === 'azera_shop_none' ) { echo 'style="display:none;"'; } ?>>
			<span class="customize-control-title">
				<?php esc_html_e( 'Image','azera-shop' )?>
			</span>
			<input type="text" class="widefat custom-media-url" value="<?php echo esc_attr( $value ); ?>">
			<input type="button" class="button button-secondary customizer-repeater-custom-media-button" value="<?php esc_attr_e( 'Upload Image','azera-shop' ); ?>" />
		</div>
		<?php
	}

	/**
	 * If both image and icon controls are enabled display a dropdown to chose between
	 * those two
	 *
	 * @param string $value Dropdown value.
	 */
	private function icon_type_choice( $value = 'azera_shop_icon' ) {
	?>
		<span class="customize-control-title">
			<?php esc_html_e( 'Image type','azera-shop' );?>
		</span>
		<select class="customizer-repeater-image-choice">
			<option value="azera_shop_icon" <?php selected( $value,'azera_shop_icon' );?>><?php esc_html_e( 'Icon','azera-shop' ); ?></option>
			<option value="azera_shop_image" <?php selected( $value,'azera_shop_image' );?>><?php esc_html_e( 'Image','azera-shop' ); ?></option>
			<option value="azera_shop_none" <?php selected( $value,'azera_shop_none' );?>><?php esc_html_e( 'None','azera-shop' ); ?></option>
		</select>
		<?php
	}

	/**
	 * Function to display social repeater control.
	 *
	 * @param string $value Input value.
	 */
	private function repeater_control( $value = '' ) {
		$social_repeater = array();
		$show_del        = 0; ?>
		<span class="customize-control-title"><?php esc_html_e( 'Social icons', 'azera-shop' ); ?></span>
		<?php
		if ( ! empty( $value ) ) {
			$social_repeater = json_decode( html_entity_decode( $value ), true );
		}
		if ( ( count( $social_repeater ) == 1 && '' === $social_repeater[0] ) || empty( $social_repeater ) ) { ?>
			<div class="customizer-repeater-social-repeater">
				<div class="customizer-repeater-social-repeater-container">
					<div class="customizer-repeater-rc input-group icp-container">
						<input data-placement="bottomRight" class="icp icp-auto" value="<?php if ( ! empty( $value ) ) { echo esc_attr( $value ); } ?>" type="text">
						<span class="input-group-addon"></span>
					</div>
					<?php get_template_part( $this->azera_shop_icon_container ); ?>
					<input type="text" class="customizer-repeater-social-repeater-link"
					       placeholder="<?php esc_attr_e( 'Link', 'azera-shop' ); ?>">
					<input type="hidden" class="customizer-repeater-social-repeater-id" value="">
					<button class="social-repeater-remove-social-item" style="display:none">
						<?php esc_html_e( 'Remove Icon', 'azera-shop' ); ?>
					</button>
				</div>
				<input type="hidden" id="social-repeater-socials-repeater-colector" class="social-repeater-socials-repeater-colector" value=""/>
			</div>
			<button class="social-repeater-add-social-item button-secondary"><?php esc_html_e( 'Add icon', 'azera-shop' ); ?></button>
			<?php
		} else { ?>
			<div class="customizer-repeater-social-repeater">
				<?php
				foreach ( $social_repeater as $social_icon ) {
					$show_del ++; ?>
					<div class="customizer-repeater-social-repeater-container">
						<div class="customizer-repeater-rc input-group icp-container">
							<input data-placement="bottomRight" class="icp icp-auto" value="<?php if ( ! empty( $social_icon['icon'] ) ) { echo esc_attr( $social_icon['icon'] ); } ?>" type="text">
							<span class="input-group-addon"><i class="fa <?php echo esc_attr( $social_icon['icon'] ); ?>"></i></span>
						</div>
						<?php get_template_part( $this->azera_shop_icon_container ); ?>
						<input type="text" class="customizer-repeater-social-repeater-link"
						       placeholder="<?php esc_html_e( 'Link', 'azera-shop' ); ?>"
						       value="<?php if ( ! empty( $social_icon['link'] ) ) {
									echo esc_url( $social_icon['link'] );
} ?>">
						<input type="hidden" class="customizer-repeater-social-repeater-id"
						       value="<?php if ( ! empty( $social_icon['id'] ) ) {
									echo esc_attr( $social_icon['id'] );
} ?>">
						<button class="social-repeater-remove-social-item"
						        style="<?php if ( $show_del == 1 ) {
							        echo 'display:none';
} ?>"><?php esc_html_e( 'Remove Icon', 'azera-shop' ); ?></button>
					</div>
					<?php
				} ?>
				<input type="hidden" id="social-repeater-socials-repeater-colector"
				       class="social-repeater-socials-repeater-colector"
				       value="<?php echo esc_textarea( html_entity_decode( $value ) ); ?>" />
			</div>
			<button class="social-repeater-add-social-item button-secondary"><?php esc_html_e( 'Add icon', 'azera-shop' ); ?></button>
			<?php
		}// End if().
	}
}
