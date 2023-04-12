import React from 'react';
import styles from './Delivery.module.scss'
import maps from '../../images/maps.png'
import minPomidor from '../../images/min-pomidor.png'
import salat from '../../images/salat.png'


function Delivery(props) {
	return (
		<div className={styles.delivery}>
			<img className={styles.minPomidor} src={minPomidor} alt="" />
			<img className={styles.salat} src={salat} alt="" />
			<div className={styles.delivery__title}>
				<div></div>
				<h3>Как оформить заказ</h3>
			</div>
			<div className={styles.delivery__order}>
				<p className={styles.delivery__orderName}>«Папа Лаваш» осуществляет доставку каждый день 24/7.</p>
				<h6>Заказ можно оформить:</h6>
				<ul>
					<li>На нашем сайте www.papalavash.ru</li>
					<li>По телефону <a href="tel:906906">906-906</a> , <a href="tel:569659"> 569-659</a></li>
					<li>В приложениях «Яндекс. Еда», «Деливери Клаб», «Сhibbis».</li>
				</ul>
				<h6>Оплата</h6>
				<p className={styles.payText}> Свой заказ вы можете оплатить как наличными, так и банковской картой.</p>
				<p className={styles.middleText}>Минимальный заказ от 300 руб.</p>
			</div>

			<div className={styles.delivery__title}>
				<div></div>
				<h3>Зоны доставки</h3>
			</div>
			<div className={styles.delivery__orderZona}>
				<h6 className={styles.greenZona}>Зеленая зона</h6>
				<p>Доставка - <span className={styles.middleText}>80 руб.</span> для следующих районов: Установский, Индустриальный, Первомайский, Октябрьский районы.</p>
				<p className={styles.bottomText}>Бесплатная доставка от 600 руб.</p>
				
				<h6 className={styles.redZona}>Красная зона</h6>
				<p>Доставка - <span className={styles.middleText}> 160 руб.</span> для следующих районов:</p>
				<p>Ленинский район ж/д Вокзал, Строитель, Тат. Базар, Машиностроитель, СХВ, Малиновая гора, п. Октябрьский, п. Первомайский, Шунды, п. Пирогово, п. Радужный, Вараксино, Липовая Роща.</p>
				<p className={styles.bottomText}>Бесплатная доставка от 600 руб.</p>

				<h6 className={styles.blackZona}>Черная зона</h6>
				<p>Доставка - <span className={styles.middleText}> 210 руб.</span> для следующих районов: Хохряки, Медведево, Октябрьский.</p>
				<p className={styles.bottomText}>Бесплатная доставка от 2000 руб.</p>

				<h6 className={styles.country}>За город</h6>
				<p>Доставка - <span className={styles.middleText}> 260 руб.</span> для районов: Старки, Пазелы, Биатлон, Кост.Мельница, Люли, Старый полигон, Смирново, п. Позимь, Нижний Вожой, Новые Парники, Орловское, Игерман, Малиново, Старый Чультем, Новый Чультем, Италмас, Ягул, Русский Вожжой, Русь, Завьялово, ст.Мартьяново,
  					Бесплатной доставки в эти указанные районы нет.</p>
				<p className={styles.bottomText}>Бесплатной доставки в эти указанные районы нет</p>
			</div>

			<div className={styles.delivery__title}>
				<div></div>
				<h3>Зоны доставки</h3>
			</div>

			<iframe className={styles.map} src="https://yandex.ru/map-widget/v1/?um=constructor%3A9383bce9586a2832fddd819314dd5b97a1167f44aa80d9af97b0aa74c93af91c&amp;source=constructor"></iframe>
		</div>
	);
}

export default Delivery;

