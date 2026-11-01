<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$sample = array(
	array( 1, -1, 0, 6 ), array( 2, 0, 1, 7 ), array( 3, 1, 1, 7.5 ), array( 4, 0, 0, 6 ),
	array( 5, -1, 1, 6.5 ), array( 6, 0, 2, 8 ), array( 7, 1, 1, 7 ),
);
$series = array( 'mood' => array( 1, -3, 3 ), 'energy' => array( 2, 0, 2 ), 'sleep' => array( 3, 0, 12 ) );
$sections = array(
	array( 'diary/', 'Дневник настроения', 'Записи о твоём состоянии' ),
	array( 'history/', 'История', 'Результаты тестов' ),
	array( 'saved/', 'Сохранённое', 'Письма, планы и находки из практик' ),
	array( 'notes/', 'Заметки', 'Твои личные записи' ),
	array( '', 'Избранное', 'Практики, к которым хочется вернуться' ),
	array( 'data/', 'Управление данными', 'Перенос и удаление данных' ),
);
?>
<div class="gz-vdoh-cabinet" data-vdoh-cabinet aria-describedby="vdoh-cabinet-note">
	<p class="gz-vdoh-demo-note" id="vdoh-cabinet-note">Интерактивный обзор кабинета. Запись и значения графика вымышлены.</p>
	<div class="gz-vdoh-tabs" data-vdoh-tabs role="group" aria-label="Экраны кабинета" aria-describedby="vdoh-tabs-hint" hidden>
		<p class="gz-vdoh-tabs-hint" id="vdoh-tabs-hint">Нажмите на вкладку, чтобы посмотреть другой экран ↓</p>
		<button type="button" data-vdoh-tab="overview" aria-controls="vdoh-panel-overview" aria-pressed="true">Моё</button>
		<button type="button" data-vdoh-tab="records" aria-controls="vdoh-panel-records" aria-pressed="false">Запись в дневнике</button>
		<button type="button" data-vdoh-tab="stats" aria-controls="vdoh-panel-stats" aria-pressed="false">Статистика</button>
	</div>
	<section class="gz-vdoh-cabinet-panel" data-vdoh-panel="overview" id="vdoh-panel-overview" aria-labelledby="vdoh-mine-title">
		<div class="gz-vdoh-panel-heading"><h3 id="vdoh-mine-title">Моё</h3><span>Хранится только на этом устройстве</span></div>
		<ul class="gz-vdoh-mine-links">
			<?php foreach ( $sections as $i => $section ) : ?>
			<li><span aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span><div><h4><?php echo esc_html( $section[1] ); ?></h4><p><?php echo esc_html( $section[2] ); ?></p></div><span aria-hidden="true">↗</span></li>
			<?php endforeach; ?>
		</ul>
		<a class="gz-text-action" href="https://vdoh.greendiz.ru/moe/" target="_blank" rel="noopener noreferrer">Открыть настоящий кабинет ↗</a>
	</section>
	<section class="gz-vdoh-cabinet-panel" data-vdoh-panel="records" id="vdoh-panel-records" aria-labelledby="vdoh-record-title">
		<div class="gz-vdoh-panel-heading"><h3 id="vdoh-record-title">Запись в дневнике</h3><span>Пример за 7 октября</span></div>
		<div class="gz-vdoh-record-grid"><div><p class="gz-vdoh-small-title">Как ты сейчас?</p><div class="gz-vdoh-mood-scale" aria-label="Настроение: плюс один, хорошо"><span>−3</span><span>−2</span><span>−1</span><span>0</span><span class="is-selected">+1</span><span>+2</span><span>+3</span></div><p class="gz-vdoh-hand">от «тяжело» до «через край»</p><dl class="gz-vdoh-record-values"><div><dt>Сколько сил?</dt><dd>Достаточно</dd></div><div><dt>Чувства</dt><dd>Спокойствие</dd></div><div><dt>Сон прошлой ночью</dt><dd>7 часов</dd></div></dl></div><div class="gz-vdoh-record-note"><p class="gz-vdoh-small-title">Заметка</p><p>После прогулки удалось немного переключиться. Хочется оставить вечер свободным.</p><p class="gz-vdoh-demo-note">Это пример заполнения, а не реальная запись пользователя.</p></div></div>
		<p>В приложении запись можно изменить, дополнить или удалить. После сохранения — перейти к подходящим практикам.</p>
	</section>
	<section class="gz-vdoh-cabinet-panel" data-vdoh-panel="stats" id="vdoh-panel-stats" aria-labelledby="vdoh-stats-title">
		<div class="gz-vdoh-panel-heading"><h3 id="vdoh-stats-title">Статистика</h3><span>Демонстрация · 1–7 октября</span></div>
		<div class="gz-vdoh-chart" data-vdoh-chart>
			<svg viewBox="0 0 640 270" role="img" aria-labelledby="vdoh-chart-title vdoh-chart-desc">
				<title id="vdoh-chart-title">Настроение, силы и сон — пример за семь дней</title><desc id="vdoh-chart-desc">Вымышленные значения. Настроение — сплошная линия с кругами, силы — штриховая с квадратами, сон — пунктир с ромбами. Значения доступны в таблице под графиком. Разные шкалы не означают равенства показателей.</desc>
				<?php foreach ( array( 36, 126, 216 ) as $i => $y ) : ?><line class="gz-vdoh-chart-grid" x1="48" x2="582" y1="<?php echo absint( $y ); ?>" y2="<?php echo absint( $y ); ?>"/><text x="30" y="<?php echo absint( $y + 5 ); ?>" text-anchor="end"><?php echo esc_html( array( '+3', '0', '−3' )[ $i ] ); ?></text><text x="596" y="<?php echo absint( $y + 5 ); ?>"><?php echo esc_html( array( '12 ч', '6 ч', '0 ч' )[ $i ] ); ?></text><?php endforeach; ?>
				<?php foreach ( $series as $key => $scale ) : ?>
					<?php
					$points = array();
					foreach ( $sample as $i => $day ) {
						$points[] = array( 48 + $i * 89, 216 - ( $day[ $scale[0] ] - $scale[1] ) / ( $scale[2] - $scale[1] ) * 180 );
					}
					$polyline = implode( ' ', array_map( function ( $point ) { return implode( ',', $point ); }, $points ) );
					?>
					<g class="gz-vdoh-chart-<?php echo esc_attr( $key ); ?>" data-vdoh-series="<?php echo esc_attr( $key ); ?>">
						<polyline points="<?php echo esc_attr( $polyline ); ?>"/>
						<?php foreach ( $points as $point ) : ?>
							<?php if ( $key === 'mood' ) : ?><circle cx="<?php echo esc_attr( $point[0] ); ?>" cy="<?php echo esc_attr( $point[1] ); ?>" r="4"/>
							<?php elseif ( $key === 'energy' ) : ?><rect x="<?php echo esc_attr( $point[0] - 3.5 ); ?>" y="<?php echo esc_attr( $point[1] - 3.5 ); ?>" width="7" height="7"/>
							<?php else : ?><path d="<?php echo esc_attr( 'M' . $point[0] . ' ' . ( $point[1] - 5 ) . 'l5 5-5 5-5-5Z' ); ?>"/><?php endif; ?>
						<?php endforeach; ?>
					</g>
				<?php endforeach; ?>
				<?php foreach ( $sample as $i => $day ) : ?><text x="<?php echo absint( 48 + $i * 89 ); ?>" y="250" text-anchor="middle"><?php echo absint( $day[0] ); ?>.10</text><?php endforeach; ?>
			</svg>
		</div>
		<p data-vdoh-empty role="status" hidden>Включи линии в легенде.</p>
		<p class="gz-vdoh-chart-key"><span>● Настроение</span><span>■ Силы</span><span>◆ Сон</span></p>
		<div class="gz-vdoh-chart-legend" data-vdoh-legend hidden>
			<label><input type="checkbox" data-vdoh-line="mood" checked><span>Настроение<small>От −3 до +3 · шкала слева</small></span></label>
			<label><input type="checkbox" data-vdoh-line="energy" checked><span>Силы<small>Мало — низ, достаточно — середина, много — верх</small></span></label>
			<label><input type="checkbox" data-vdoh-line="sleep" checked><span>Сон<small>Часы · шкала справа</small></span></label>
		</div>
		<p class="gz-vdoh-demo-note">У линий разные шкалы. Совпадение по высоте не означает одинаковые значения. В приложении доступны периоды 7 и 30 дней; этот пример показывает семь.</p>
		<details class="gz-vdoh-chart-data"><summary>Посмотреть демонстрационные значения</summary><div class="gz-vdoh-data-scroll" tabindex="0" role="region" aria-label="Значения графика"><table><caption>Вымышленные данные за 1–7 октября</caption><thead><tr><th scope="col">Дата</th><th scope="col">Настроение</th><th scope="col">Силы</th><th scope="col">Сон</th></tr></thead><tbody><?php foreach ( $sample as $day ) : ?><tr><th scope="row"><?php echo absint( $day[0] ); ?>.10</th><td><?php echo esc_html( $day[1] > 0 ? '+' . $day[1] : $day[1] ); ?></td><td><?php echo esc_html( array( 'Мало', 'Достаточно', 'Много' )[ $day[2] ] ); ?></td><td><?php echo esc_html( str_replace( '.', ',', (string) $day[3] ) ); ?> ч</td></tr><?php endforeach; ?></tbody></table></div></details>
	</section>
</div>

