import React from 'react';
import styles from './Logo.module.scss'
import logo from '../../images/logo.png'
import {Link} from "react-router-dom";


function Logo(props) {
	return (
		<Link to="/">
			<div className={styles.logo} >
				<img className={styles.logo__img} src={logo} alt="logo" />
				<span className={styles.logo__text}>папа лаваш</span>
			</div>
		</Link>
	);
}

export default Logo;