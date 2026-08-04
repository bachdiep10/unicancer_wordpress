<?php
/**
 * Elementor Widget: Doctor Card
 *
 * @package UNI_ASIA
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Uniasia_Widget_Doctor_Card extends \Elementor\Widget_Base {

	public function get_name() {
		return 'uniasia-doctor-card';
	}

	public function get_title() {
		return __( 'UNI-ASIA Doctor Card', 'uniasia' );
	}

	public function get_icon() {
		return 'fa fa-user-md';
	}

	public function get_categories() {
		return array( 'uniasia-widgets' );
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'content_section',
			array(
				'label' => __( 'Doctor', 'uniasia' ),
			)
		);

		$this->add_control(
			'doctor_id',
			array(
				'label'   => __( 'Select Doctor', 'uniasia' ),
				'type'    => \Elementor\Controls_Manager::SELECT2,
				'options' => $this->get_doctors_list(),
				'label_block' => true,
			)
		);

		$this->add_control(
			'show_image',
			array(
				'label'   => __( 'Show Image', 'uniasia' ),
				'type'    => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'show_bio',
			array(
				'label'   => __( 'Show Bio', 'uniasia' ),
				'type'    => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->end_controls_section();
	}

	private function get_doctors_list() {
		$doctors = get_posts( array(
			'post_type'      => 'doctor',
			'posts_per_page' => -1,
		) );

		$options = array( '' => __( '— Select —', 'uniasia' ) );
		foreach ( $doctors as $doctor ) {
			$options[ $doctor->ID ] = $doctor->post_title;
		}
		return $options;
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$doctor_id = ! empty( $settings['doctor_id'] ) ? $settings['doctor_id'] : 0;

		if ( ! $doctor_id ) {
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				echo '<div class="elementor-alert elementor-alert-warning">' . esc_html__( 'Please select a doctor.', 'uniasia' ) . '</div>';
			}
			return;
		}

		$doctor = get_post( $doctor_id );
		if ( ! $doctor ) {
			return;
		}

		$degree   = get_field( 'doctor_degree', $doctor_id );
		$bio      = get_field( 'doctor_short_bio', $doctor_id );
		$position = get_field( 'doctor_position', $doctor_id );
		?>
		<article class="doctor-card" itemscope itemtype="https://schema.org/Physician">
			<a href="<?php echo esc_url( get_permalink( $doctor_id ) ); ?>" class="doctor-card-link">
				<?php if ( 'yes' === $settings['show_image'] && has_post_thumbnail( $doctor_id ) ) : ?>
					<div class="doctor-card-image">
						<?php echo get_the_post_thumbnail( $doctor_id, 'uniasia-doctor' ); ?>
					</div>
				<?php endif; ?>

				<div class="doctor-card-body">
					<?php if ( $degree ) : ?>
						<div class="doctor-card-degree" itemprop="jobTitle"><?php echo esc_html( $degree ); ?></div>
					<?php endif; ?>

					<h3 class="doctor-card-name" itemprop="name"><?php echo esc_html( $doctor->post_title ); ?></h3>

					<?php if ( 'yes' === $settings['show_bio'] && $bio ) : ?>
						<p class="doctor-card-bio" itemprop="description">
							<?php echo esc_html( wp_trim_words( $bio, 30 ) ); ?>
						</p>
					<?php endif; ?>

					<?php if ( $position ) : ?>
						<div class="doctor-card-position"><?php echo esc_html( $position ); ?></div>
					<?php endif; ?>

					<div class="doctor-card-action">
						<span><?php esc_html_e( 'Xem chi tiết', 'uniasia' ); ?></span>
						<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6z"/></svg>
					</div>
				</div>
			</a>
		</article>
		<?php
	}
}