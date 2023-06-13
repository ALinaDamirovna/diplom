import React, { useContext, useEffect } from 'react';
import Logo from '../Logo/Logo';
import userIcon from '../../images/icon1.svg'
import basketIcon from '../../images/icon2.svg'
import styles from './Header.module.scss'
import Container from '../Container/Container';
import { useState } from 'react';
import {Link} from "react-router-dom";
import { Context } from './../../Context';


	
function Header(props) {
	const {basketProducts, basketCount, setBasketCount, isAuth, setCity, setKladrId} = useContext(Context);
	const [burgerMenu, setBurgerMenu] = useState(false)

	const cityHandler=(e)=>{
		setCity(e.target.value)
		switch (e.target.value) {
			case "Ижевск":
				setKladrId('1800000100000')
			  break;
			case "Казань":
				setKladrId('1600000100000')
			  break;
			default:
				setKladrId('1800000100000')
		 }
	}

	const basketCountHandler = () =>{
		let count = 0
		basketProducts.map(e=>{
			count = count + e.quantity
		})
		setBasketCount(count)
	}

	const burgerMenuHandler = () =>{
		setBurgerMenu(!burgerMenu)
	}

	useEffect(()=>{
		basketCountHandler()
	}, [basketProducts])

	return (
		<div className={styles.header}>
			<Container>

				<div className={styles.header__wrapper}>
					<div onClick={setBurgerMenu} className={styles.burgerBtn}>
						<div></div>
						<div></div>
						<div></div>
					</div>

					<div onClick={burgerMenuHandler} className={burgerMenu? styles.burgerMenuBack : styles.off}>
						<div onClick={(e)=>{e.stopPropagation()}} className={burgerMenu? styles.burgerMenu : styles.off}>
							<div className={styles.burgerMenu__closeWrap}>
								<div onClick={burgerMenuHandler} className={styles.burgerMenu__close}></div>
							</div>
							<nav className={styles.burgerMenu__wrap}>
								<span className={styles.burgerMenu__delivery} >Доставка 24/7</span>
								<a className={styles.burgerMenu__phone} href="tel:906906" type="phone"> 906-906</a>
								<Link to="/delivery" onClick={burgerMenuHandler} className={styles.burgerMenu__some} >О доставке</Link>
								<Link to="/contacts" onClick={burgerMenuHandler} className={styles.burgerMenu__some} >Контакты</Link>
								<div className={styles.burgerMenu__sity}>
									<span>Выбрать город: </span>
									<select onChange={cityHandler} className={styles.burgerMenu__select}>
										<option value="Ижевск">Ижевск</option>
									</select>
								</div>
							</nav>
						</div>
					</div>
					


					<div className={styles.header__logoWrap}>
						<Logo/>
						<div className={styles.header__sity}>
							<span>Выбрать город: </span>
							<select onChange={cityHandler} className={styles.header__select}>
								<option value="Ижевск">Ижевск</option>
							</select>
						</div>
					</div>

					<div className={styles.header__navWrap}>
						<nav className={styles.header__nav}>
							<Link to="/delivery">О доставке</Link>
							<Link to="/contacts">Контакты</Link>
							<span className={styles.header__delivery} >Доставка 24/7</span>
							<a className={styles.header__phone} href="tel:906906" type="phone"> 906-906</a>
						</nav>

						<div className={styles.header__navIcon}>
							{
								<Link to="/account"><img className={styles.userIcon} src={userIcon} alt="user" /></Link>
							}
							<Link to="/basket">
								<div className={styles.header__busket}>
									<img className={styles.basketIcon} src={basketIcon} alt="busket" />
									<span className={styles.header__busCount}>{basketCount}</span>
								</div>
							</Link>
						</div>
					</div>
				</div>

			</Container>
		</div>
	);
}

export default Header;