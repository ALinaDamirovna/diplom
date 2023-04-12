import React, { useState, useContext, useEffect } from 'react';
import {Link} from "react-router-dom"
import { Context } from '../../Context';
import styles from './PayStatus.module.scss'



function PayStatus(props) {
	const[status, setStatus] = useState(true)
	const {orderStatus, setOrderStatus} = useContext(Context)


	useEffect(() => {
		console.log(orderStatus)
	},[])

	return (
		<div className={styles.payStatusWrap}>
			{
				status?
				<div className={styles.payStatus}>
					<h1 className={styles.payStatus__success}>Заказ оформлен</h1>
					<div className={styles.payStatus__buttons}>
						<Link className={styles.payStatus__btn} to="/">На главную</Link>

						<Link className={styles.payStatus__btn} to="/account/history">Отследить заказ</Link>
					</div>
				</div>
				:
				<div className={styles.payStatus}>
					<h1 className={styles.payStatus__failed}>Оплата не прошла!</h1>
					<div className={styles.payStatus__buttons}>
						<Link className={styles.payStatus__btn} to="/">На главную</Link>
						<Link className={styles.payStatus__btn} to="/basket">Назад</Link>
					</div>
				</div>
			}
		</div>
	);
}

export default PayStatus;