<?php
/**
 * Elementor Widget: Stats Counter
 *
 * @package UNI_ASIA
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Uniasia_Widget_Stats_Counter extends \Elementor\Widget_Base {

	public function get_name() {
		return 'uniasia-stats-counter';
	}

	public function get_title() {
		return __( 'UNI-ASIA Stats Counter', 'uniasia' );
	}

	public function get_icon() {
		return 'fa fa-counter';
	}

	public function get_categories() {
		return array( 'uniasia-widgets' );
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'content_section',
			array(
				'label' => __( 'Stats', 'uniasia' ),
			)
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'number',
			array(
				'label'   => __( 'Number', 'uniasia' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => '100,000+',
			)
		);

		$repeater->add_control(
			'label',
			array(
				'label'   => __( 'Label', 'uniasia' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Lượt khám', 'uniasia' ),
			)
		);

		$repeater->add_control(
			'icon',
			array(
				'label'   => __( 'Icon', 'uniasia' ),
				'type'    => \Elementor\Controls_Manager::ICONS,
			)
		);

		$this->add_control(
			'stats',
			array(
				'label'       => __( 'Stats Items', 'uniasia' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'number' => '750,000+',
						'label'  => __( 'Ca phẫu thuật', 'uniasia' ),
					),
					array(
						'number' => '20,000+',
						'label'  => __( 'Bệnh nhân / năm', 'uniasia' ),
					),
					array(
						'number' => '1,000,000+',
						'label'  => __( 'Lượt khám', 'uniasia' ),
					),
				),
				'title_field' => '{{{ number }}} - {{{ label }}}',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_section',
			array(
				'label' => __( 'Style', 'uniasia' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'number_color',
			array(
				'label'   => __( 'Number Color', 'uniasia' ),
				'type'    => \Elementor\Controls_Manager::COLOR,
				'default' => '#0066a4',
			)
		);

		$this->add_control(
			'columns',
			array(
				'label'   => __( 'Columns', 'uniasia' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => '3',
				'options' => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$columns  = ! empty( $settings['columns'] ) ? $settings['columns'] : 3;
		?>
		<div class="uniasia-stats-counter" style="--cols: <?php echo esc_attr( $columns ); ?>; --number-color: <?php echo esc_attr( $settings['number_color'] ); ?>">
			<div class="uniasia-stats-grid">
				<?php foreach ( $settings['stats'] as $stat ) : ?>
					<div class="stat-item">
						<?php if ( ! empty( $stat['icon']['value'] ) ) : ?>
							<div class="stat-icon"><?php \Elementor\Icons_Manager::render_icon( $stat['icon'] ); ?></div>
						<?php endif; ?>
						<div class="stat-number"><?php echo esc_html( $stat['number'] ); ?></div>
						<div class="stat-label"><?php echo esc_html( $stat['label'] ); ?></div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}
}