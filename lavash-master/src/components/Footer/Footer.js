import React from 'react';
import Container from '../Container/Container';
import Logo from '../Logo/Logo';
import vk from '../../images/vk.svg'
import phone from '../../images/phone.svg'
import styles from './Footer.module.scss'  
import { Link } from 'react-router-dom';


function Footer(props) {
	return (
		<div className={styles.footer}>
			<Container>
				<div className={styles.footer__wrapper}>
					<div className={styles.footer__contWrap}>
						<div className={styles.off}>
							<Logo />
						</div>
						<div className={styles.footer__contacts}>
							<a href="https://vk.com/papalavash" target="_blank">
								<img src={vk} alt="vk" />
							</a>
							<a href="tel:906-906" className={styles.footer__phoneWrap}>
								<img src={phone} alt="phone" />
								<span className={styles.footer__phoneNum}>906-906</span>
							</a>
						</div>
					</div>

					<div className={styles.footer__info}>
						<Link to="/delivery"> О доставке </Link>
						<Link to="/contacts"> Контакты </Link>
						<a href="https://franchise.papalavash.ru/" target="_blank" >Франшиза</a>
					</div>

					<div className={styles.footer__docs}>
						<Link to="/privacyPolicy">Политика конфиденциальности</Link>
						<Link to="/publicOffer">Публичная оферта</Link>
					</div>

				</div>
			</Container>
		</div>
	);
}

export default Footer;