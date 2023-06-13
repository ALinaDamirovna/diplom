import React, { useContext, useEffect, useState } from 'react';
import { Context } from '../../Context';
import styles from './OrderModal.module.scss'

function Modal(props) {
	const [order, setOrder] = useState()
	const {orderStatus, setOrderStatus} = useContext(Context)

	var requestOptions = {
		method: 'GET',
		redirect: 'follow'
	 };
	async function getOrderStatus() {
		await fetch(`https://lavash.endlessmind.space/api/order/${orderStatus.id}/${orderStatus.hash}`, requestOptions)
				.then(response => response.json())
				.then(result => {
					console.log(result) 
					setOrder(result)
				})
				.catch(error => console.log('error', error));
	}


	useEffect(() => {
		if(orderStatus){
			getOrderStatus()
		}
	},[orderStatus])
	return (
		props.orderModal?
			<div 
				onClick={props.onClick}
 				className={props.orderModal? styles.modalBack : styles.closeModal}
			>
				<div onClick={(e)=>{e.stopPropagation()}} className={styles.cardModal}>
					<div onClick={props.onClick} className={styles.cardModal__close}>&#10006;</div>
					<h3 className={styles.status__success}>Заказ успешно оформлен!</h3>
					<p className={styles.orderStatus} >Статус заказа: {order? order.statusName: null}</p>	
					<p className={styles.orderText}>Не закрывайте пожалуйста данную страницу</p>
				</div>
			</div>
		:
		null
	);
}

export default Modal;