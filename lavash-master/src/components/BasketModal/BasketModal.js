import React, { useContext, useEffect, useState } from 'react';
import styles from './BasketModal.module.scss'
import MoreItem from '../MoreItem/MoreItem';
import { Context } from '../../Context';



function BasketModal(props) {
	const {basketProducts, setBasketProducts} = useContext(Context)
	const [product, setProduct] = useState();
	const [totalPrice, setTotalPrice] = useState(props.startPrice);
	const [options, setOptions] = useState([]);
	const [additions, setAdditions] = useState([]);
	const [count, setCount] = useState(1);
	


	const countUpHandler = () =>{
		setCount(count + 1)
	}
	const countDownHandler = () =>{
		if(count > 0){
			setCount(count - 1);
		}
	}
	const requestOptions = {
		method: 'GET',
		redirect: 'follow'
	};

	async function getProduct (){
		await fetch(`https://lavash.endlessmind.space/api/products/${props.id}`, requestOptions)
		.then(response => response.json())
		.then(result => {
			setProduct(result)
		})
		.catch(error => console.log('error', error));
	}


	const sendToLocalStorage = () =>{
		localStorage.setItem('basketProducts', JSON.stringify(basketProducts))
	}

	const addToBasket = (e) => {
		let basProducts = basketProducts
		if(count == 0){
			alert( "Выбрано товаров 0" );
		}else{
			basProducts.map((elem, index)=>{
				if(props.orderId == elem.orderId){
					basProducts.splice(index, 1)
				}
			})
			basProducts.push({
					"id":					props.id,
					"orderId":        Math.floor(Math.random() * 1000) + 1,
					"name":	         product.name,
					"price":	         totalPrice,
					"img":         	product.photo,
					"quantity":       count,
					"options":        options,
					"additions":      additions
				}
			)
		}
		setBasketProducts(basProducts)
		sendToLocalStorage()
		if(count !== 0 ) {
			props.onClick()
		}
		window.location.reload();
	}

	const totalPriceHandler = () => {
		let optionsPrice = 0;
		let additionsPrice = 0;
		if(options.length !== 0){
			options.map((e)=>{
				optionsPrice = optionsPrice + Number(e.price)
			})
		}else{
			optionsPrice = 0;
		}
		if(additions.length !== 0){
			additions.map((e)=>{
				additionsPrice = additionsPrice + e.price
			})
		} else{
			additionsPrice = 0;
		}
		if(product){
			let tPrice = count * (product.price + optionsPrice + additionsPrice)
			setTotalPrice(tPrice)
		}
	}

	
	const optionsHandler = (e) => {
		let opt = options
		if(opt.length == 0){
			 opt.push({
				"id": e.target.dataset.groupid,
				"value": e.target.id,
				"price":	parseInt(e.target.dataset.price)
			})
		}else{
			opt.map(elem => {
				if(e.target.dataset.groupid == elem.id){
					elem.value = e.target.id
					elem.price = parseInt(e.target.dataset.price)
				}else{
					opt.push({
						"id": e.target.dataset.groupid,
						"value": e.target.id,
						"price":	parseInt(e.target.dataset.price)
					})
				}
			})

			if(opt.length > 2){
				for(let i = 0; i < opt.length; i++){
					for(let j = i+1; j < opt.length; j++){
						if(opt[i].id == opt[j].id && opt[i].value == opt[j].value){
							opt.splice(j, 1)
						}
						
					}
				}
			}
		}
		setOptions(opt)
		totalPriceHandler()
	}

	const additionsHandler = (e)=>{
		if(e){
			console.log(e)
			let add = additions
			if(add.length == 0){
				add.push(e)
			}else{
				add.map(elem => {
					if(e.id == elem.id){
						elem.quantity = e.quantity
						elem.price = e.price
					}else{
						add.push(e)
					}
				})

				if(add.length > 2){
					for(let i = 0; i < add.length; i++){
						for(let j = i+1; j < add.length; j++){
							if(add[i].id == add[j].id){
								add.splice(j, 1)
							}
						}
					}
				}
				for(let i = 0; i < add.length; i++){
					if(add[i].quantity == 0){
						add.splice(i, 1)
					}
				}
			}

			setAdditions(add)
			totalPriceHandler()
			console.log(additions)
		}
	}

	
	
	useEffect((e) =>{
		getProduct()
	},[])


	useEffect((e) =>{
		totalPriceHandler()
	},[count])


	return (
		product?
			<div 
				onClick={props.onClick}
 				className={props.modal? styles.modalBack : styles.closeModal}
			>
				<div onClick={(e)=>{e.stopPropagation()}} className={styles.cardModal}>
					<div className={styles.cardModal__closeWrap}>
						<div onClick={props.onClick} className={styles.cardModal__close}></div>
					</div>
					<div className={styles.cardModal__about}>
						<img src={product.photo} alt="img" />
						<div className={styles.cardModal__aboutDesc}>
							<div>
								<h6>Описание</h6><span>{product.weight}г</span>
							</div>
							<p>{product.desc}</p>
						</div>
						<div className={styles.cardModal__aboutStruct}>
							<h6>Состав</h6>
							<p>
								{product.composition}
							</p>
						</div>
					</div>


					<div className={styles.info}>
						<div className={styles.infoMobTitle}>
							<img src={product.photo} alt="img" />
							<div className={styles.info__titleWrap}>
								<h3 className={styles.info__title}>{product.name}</h3>
								<span className={styles.info__weight}>{product.weight}г</span> 
								<span className={styles.card__price}>{product.price}&#8381;</span>
							</div>
						</div>
						<div className={styles.info__sauceWrap}>

							{
								product.options.map(e=>(
									<div id={e.id} className={styles.info__sauce}>
										<p>{e.alias}:</p>
										<div>
											{
												e.values.map(elem=>(
													<label 

														className={styles.info__radioWrap}
													>
														<input
															onClick={optionsHandler}
															data-price={elem.price}
															data-groupId ={elem.group_id}
															id={elem.id} 
														 	className={styles.radio__real} type="radio" name={e.name}
														  />
														<span className={styles.radio__fake}></span>
														<span className={styles.radio__title}>{elem.name}</span>
													</label>
												))
											}
										</div>
									</div>
								))
							}

						</div>

						{
							product.additions.length != 0 ?
								<div className={styles.info__more}>
									<p>Добавить к заказу:</p>
									<div className={styles.info__moreWrap}>
										{
											product.additions.map(e=>(
												<MoreItem
													id = {e.id}
													price={e.price}
													moreImg={e.photo}
													moreTitle={e.name}
													getAdditions={additionsHandler}
												/>
											))
										}
										
									</div>
								</div>
							:
							null
						}
						<div className={styles.aboutDescMob}>
							<div>
								<h6>Описание</h6>
							</div>
							<p>{product.desc}</p>
						</div>
						<div className={styles.aboutStructMob}>
							<h6>Состав</h6>
							<p>
								{product.composition}
							</p>
						</div>
						<div className={styles.line}></div>
						<div className={styles.info__result}>
							<span className={styles.card__price}>{totalPrice}&#8381;</span>
							<div className={styles.card__count}>
								<span onClick={countDownHandler} className={styles.card__countDown}>-</span>
								<span>{count}</span>
								<span onClick={countUpHandler} className={styles.card__countUp}>+</span>
							</div>
							<button onClick={addToBasket} className={styles.info__resultBtn}>Сохранить изменения</button>
						</div>
					</div>
				</div>
			</div>
		:
		null
	);
}

export default BasketModal;