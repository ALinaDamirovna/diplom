import React from 'react';
import styles from './Contacts.module.scss'
import vkDark from '../../images/vk-dark.svg'
import instagram from '../../images/instagram.svg'
import chese from '../../images/chesse.png'
import salat from '../../images/salat.png'
import pomidor from '../../images/pomidor.png'


function Contacts(props) {
	return (
		<div className={styles.contacts}>
			<img  className={styles.salat} src={salat} alt="" />
			<img className={styles.pomidor} src={pomidor} alt="" />
			<img className={styles.chese} src={chese} alt="" />

			<div className={styles.contacts__title}>
				<div></div>
				<h3>Контакты</h3>
			</div>
			<div className={styles.contacts__itemWrap} >
				<div className={styles.contacts__info}>
					<h6>Телефон:</h6>
					<a  className={styles.contacts__tel} href="tel:73412906906">+7 (3412) 906-906</a>
					<p>Соц.сети:</p>
					<div className={styles.contacts__socials} >
						<a href="https://vk.com/papalavash" target="_blank"><img src={vkDark} alt="icon" /></a>
					</div>
				</div>
				{/*<div className={styles.contacts__reviews}>
					<h6>Ваши отзывы и предложения</h6>
					<input className={styles.contacts__revName} placeholder="Укажите имя" type="text" />
					<input className={styles.contacts__revEmail} placeholder="Укажите почту" type="text" />
					<textarea className={styles.contacts__revText} placeholder="Ваш комментарий" type="text" />
					<button className={styles.contacts__btn}>Отправить</button>
				</div>*/}
			</div>

			<div className={styles.contacts__title}>
				<div></div>
				<h3>Реквизиты</h3>
			</div>
			<div  className={styles.contacts__requisites}>
				<p>Продавец: Овчинникова Екатерина Александровна</p>
				<p>Почтовый адрес:</p>
				<p>426010, Удмуртская Республика, город Ижевск, ул. Азина, д. 1, оф. 304 (ОЦ «Вар-Мит»)</p>
				<p>Юридический адрес: 427168, УР, Игринский район, с. Факел, пер. Суворова, 14а </p>
				<p>ОГРН 322183200040412</p>
				<p>ИНН 180903781165</p>
				<p>р/с 180903781165</p>
				<p>к/с 30101810845250000999</p>
				<p>ТОЧКА ПАО БАНКА «ФК ОТКРЫТИЕ»</p>
				<p>БИК 044525999</p>
			</div>
		</div>
	);
}

export default Contacts;
