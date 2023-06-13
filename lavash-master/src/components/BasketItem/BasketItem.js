import React, {useContext, useEffect, useState}from 'react';
import styles from './BasketItem.module.scss'
import editImg from '../../images/edit.svg'
import { Context } from '../../Context';
import BasketModal from '../BasketModal/BasketModal';

function BasketItem(props) {
	const [count, setCount] = useState(props.count);
	const [price, setPrice] = useState(props.price);
	const [modal, setModal] = useState(false);
	const [additions, setAdditions] = useState([]);
	const [options, setOptions] = useState([]);
	const {basketProducts, setBasketProducts, basketCount, setBasketCount} = useContext(Context)



	const deteteBasketItem = (e) => {
		const newProducts = basketProducts.filter(elem => elem.orderId !== props.orderId)
		setBasketProducts(newProducts)
		window.location.reload();
	}


	const additionsHandler = ()=>{
		basketProducts.map(e=>{
			if(e.id == props.id){
				setAdditions(e.additions)
			}
		})	
	}
	const optionsHandler = ()=>{
		basketProducts.map(e=>{
			if(e.id == props.id){
				setOptions(e.options)
			}
		})	
	}

	const modalHandler = (e) =>{
		setModal(!modal)
	}

	const sendToLocalStorage = () =>{
		localStorage.setItem('basketProducts', JSON.stringify(basketProducts))
	}

	const countUpHandler = () =>{
		let itemPrice = price / count
		setCount(count + 1)
		let bProducts = basketProducts
		bProducts.map(e=>{
			if(e.id == props.id){
				e.quantity = e.quantity + 1
				e.price = price + itemPrice
				setPrice(price + itemPrice)
			}
		})
		setBasketProducts(bProducts)
		sendToLocalStorage()
		props.setTotalBasketPrice(props.totalBasketPrice + itemPrice)
		basketCountHandler()
	}
	const countDownHandler = () =>{
		if(count > 1){
			let itemPrice = price / count
			setCount(count - 1)
			let bProducts = basketProducts
			bProducts.map(e=>{
				if(e.id == props.id){
					e.quantity = e.quantity - 1
					e.price = price - itemPrice
					setPrice(price - itemPrice)
				}
				setBasketProducts(bProducts)
				sendToLocalStorage()
				props.setTotalBasketPrice(props.totalBasketPrice - itemPrice)
				basketCountHandler()
			})
		}
	}
	const basketCountHandler = () =>{
		let count = 0
		basketProducts.map(e=>{
			count = count + e.quantity
		})
		setBasketCount(count)
	}

	useEffect(()=>{
		additionsHandler()
		optionsHandler()
		console.log(basketProducts)
	},[])
	

	return (
		<div className={styles.basketItem}>
			<span onClick={deteteBasketItem} className={styles.closeItem}>&#10006;</span>
			<div className={styles.basketItem__Wrap}>
				<img className={styles.basketItem__img} src={props.img} alt="img" />
				<div className={styles.basketItem__info}>
					<span className={styles.basketItem__title}>{props.title}</span>
					<div className={styles.basketItem__infoDetails}>
						<div className={styles.count}>
							<span onClick={countDownHandler} className={styles.countDown}>-</span>
							<span>{count}</span>
							<span onClick={countUpHandler} className={styles.countUp}>+</span>
						</div>
						<img onClick={modalHandler} className={styles.editBtn} src={editImg} alt="" />
					</div>
				</div>
				<span  className={styles.basketItem__price}>{price}&#8381;</span>
			</div>
			<ul className={styles.basketItem__additions}>
				{
					options?
					options.map(e=>(
						<li>+ {e.name} <span>соус</span></li>
					))
					:
					null
				}
				{
					additions?
					additions.map(e=>(
						<li>+ {e.name} <span>x{e.quantity}</span></li>
					))	
					:
					null
				}
			</ul>

			<BasketModal
				id={props.id}
				orderId = {props.orderId}
				onClick={modalHandler}
				modal={modal}
				count={count}
				price={props.price}
				startPrice={props.startPrice}
				countUpHandler={countUpHandler}
				countDownHandler={countDownHandler}
			/>

		</div>
	);
}

export default BasketItem;