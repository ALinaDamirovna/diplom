import React, {useContext, useEffect, useState} from 'react';
import { Context } from '../../Context';
//COMPONENTS
import BasketItem from '../../components/BasketItem/BasketItem';
import OrderModal from '../../components/OrderModal/OrderModal';
import AddressHelper from '../../components/AddressHelper/AddressHelper';
//IMAGES
import home from '../../images/home.svg'
import blPhone from '../../images/bl-phone.svg'
import chesse from '../../images/chesse.png'
import salat from '../../images/salat.png'
import pomidor from '../../images/pomidor.png'
//STYLE
import styles from './Basket.module.scss'



function Basket(props) {
	//ORDER
	const [orderPrice, setOrderPrice] = useState()
	const [orderModal, setOrderModal] = useState(false);
	const {orderStatus, setOrderStatus, kladrId, user} = useContext(Context)

	//Products for basket
	const {basketProducts, setBasketProducts} = useContext(Context)
	const [basProducts, setBasProducts] = useState(JSON.parse(localStorage.getItem('basketProducts')))

	//Conditions check state
	const [conditionsChecked, setConditionsChecked] = useState(false)

	//Prices states
	const [totalBasketPrice, setTotalBasketPrice] = useState(0)
	const [alltotalBasketPrice, setAllTotalBasketPrice] = useState(0)
	const [deliveryPrice, setDeliveryPrice] = useState(0)
	
	// Delivery states
	const [streetAndHome, setStreetAndHome] = useState('')
	const [flat, setFlat] = useState('')
	const [doorphone, setDoorphone] = useState('')
	const [entrance, setEntrance] = useState('')
	const [floor, setFloor] = useState('')

	//Comment state
	const [comment, setComment] = useState('')

	//Promocode states
	const [promoCode, setPromoCode] = useState('')


	//Contact states
	const [phone, setPhone] = useState('')
	const [correctPhone, setCorrectPhone] = useState(false)
	const [name, setName] = useState('')
	const [email, setEmail] = useState('')
	const [correctEmail, setCorrectEmail] = useState(false)

	const[inputHomeAddresses, setInputHomeAddresses] = useState(false)
	const[helpAddresses, setHelpAddresses] = useState()


	const token = "2e4126073de848027d6fdc2f788080f639af4047";
	const options = {
		method: "POST",
		mode: "cors",
		headers: {
			"Content-Type": "application/json",
			"Accept": "application/json",
			"Authorization": "Token " + token,
		},
		body: JSON.stringify({
			query: streetAndHome,
			locations: [{"kladr_id": kladrId}]
		})
	}

	async function getAddres(){
		await fetch("https://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest/address", options)
		.then(response => response.json())
		.then(result => {
			console.log(result)
			setHelpAddresses(result)
		})
		.catch(error => console.log("error", error));
	}

	const streetAndHomeHandler =(e) =>{
		setStreetAndHome(e.target.value)
		getAddres()
		if(e.target.value && helpAddresses.suggestions.length != 0){
			setInputHomeAddresses(true)
		}else{
			setInputHomeAddresses(false)
		}
	}

	const emailHandler =(e) =>{
		setEmail(e.target.value)
		const re = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
		if(!re.test(String(e.target.value).toLowerCase())){
			e.target.style="border: 1px solid #c43939;"
			setCorrectEmail(false)
		}else{
			e.target.style="border: 1px solid #B9B9B9;"
			setCorrectEmail(true)
		}
	}

	function findIndex(str, sub, count){
		for(let i=0; i < str.length; i++){
			if(str[i] === sub){
				count--;
			}
			if(count === 0){
				return i;
			}
		}
	}

	const phoneHandler =(e) =>{
		//setPhone(e.target.value)
		//const re = /^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/;
		//if(!re.test(String(e.target.value).toLowerCase())){
		//	e.target.style="border: 1px solid #c43939;"
		//	setCorrectPhone(false)
		//}else{
		//	e.target.style="border: 1px solid #B9B9B9;"
		//	setCorrectPhone(true)
		//}

		
		let mask = '_ (___) ___ - __ - __';
		let text = e.target.value ;
		let phone_text = text.replace(/\D/g,'');
		let phone_length = phone_text.length;

		if(phone_length >= 11){
			phone_length = 11;
			phone_text = phone_text.slice(0, phone_length);
		}else if(phone_length < 10 || phone_length > 11){
			e.target.style="border: 1px solid #c43939";
			setCorrectPhone(false)
		}else{
			e.target.style="border: 1px solid #B9B9B9;"
			setCorrectPhone(true)
		}
		
		let slice_index = findIndex(mask, '_', phone_length)+1;
		
		
		for(let num of phone_text){
			mask = mask.replace('_', num);
		}
		
		mask = mask.slice(0, slice_index);
		if(mask[0] == 7 || mask[0] == 8){
			mask = '+7' + mask.slice(1);
		}
		e.target.value = mask;
		setPhone(e.target.value)
	}

	const changeConditions = ()=>{
		setConditionsChecked(!conditionsChecked)
	}

	const totalBasketPriceHandler = ()=>{
		let price=0;
		basProducts.map(e => {
			price = price + e.price
		})
		setTotalBasketPrice(price)
	}
	
	
	const createOrder=()=>{
		if(conditionsChecked === false){
			return alert("Необходимо согласие с Условиями использования, Правилами обработки персональных данных. Согласитесь с данным пунктом внизу страницы")
		}
		if(totalBasketPrice < 300){
			return alert('Минимальная стоимость заказа от 300 рублей')
		}
		if(!correctEmail || !correctPhone){
			return alert('Данные не корректны. Проверьте правильно ли вы указали все поля')
		}

		if(basketProducts.length !== 0 ){
			let raw = {
				"promocode": promoCode,
				"delivery": {
					"streetAndNumber": streetAndHome,
					"flat": flat,
					"doorphone": doorphone,
					"entrance": entrance,
					"floor": floor
				},
				"comment": comment,
				"contact": {
					"phone": user? user.phone: phone,
					"name": user? user.name: "",
					"email": email
				},
				"products": basketProducts
			}
			
			
			let requestOptions = {
				method: 'POST',
				body: JSON.stringify(raw),
				redirect: 'follow'
			};
			async function sendOrder(){
				await fetch(`https://lavash.endlessmind.space/api/order`, requestOptions)
				.then(response => response.json())
				.then(result => {
					console.log(result) 
					setOrderStatus(result)
				})
				.catch(error => console.log('error', error));
			}
			sendOrder()
			setOrderModal(true)
		}else{
			alert("Корзина пуста")
		}
	}



	async function calcOrder(){
		if(basketProducts.length !== 0 ){
			let raw = {
				"promocode": promoCode,
				"delivery": {
					"streetAndNumber": streetAndHome,
					"flat": flat,
					"doorphone": doorphone,
					"entrance": entrance,
					"floor": floor
				},
				"comment": comment,
				"contact": {
					"phone": user? user.phone: phone,
					"name": user? user.name: "",
					"email": email
				},
				"products": basketProducts
			}
			
			console.log(raw)
			let requestOptions = {
				method: 'POST',
				headers: {
					'Accept': 'application/json',
					'Content-Type': 'application/json'
				 },
				body: JSON.stringify(raw),
				redirect: 'follow'
			};
		await fetch(`https://lavash.endlessmind.space/api/order/calc`, requestOptions)
				.then(response => response.json())
				.then(result => {
					console.log(result) 
					setOrderPrice(result)
					setDeliveryPrice(result.delivery)
					setAllTotalBasketPrice(result.total)
				})
				.catch(error => console.log('error', error));

		}else(
			alert('Корзина пуста!')
		)
	}


	const promocodeHandler=(e)=>{
		calcOrder()
		if(orderPrice){
			if(orderPrice.discount == 0){
				alert("Промокод не сработал. Проверьте правильно ли вы его ввели!")
			}else{
				alert("Промокод применен!")
			}
		}
	}



	useEffect(()=>{
		totalBasketPriceHandler()
	},[basketProducts])

	useEffect(()=>{
		console.log(orderPrice)
	},[orderPrice])

	useEffect(()=>{
		calcOrder()
	},[totalBasketPrice])

	return (
		<div className={styles.basket}>
			<img className={styles.chesse} src={chesse} alt="img" />
			<img className={styles.pomidor} src={pomidor} alt="img" />
			<img className={styles.salat} src={salat} alt="img" />
			<div className={styles.basket__main}>
				<p className={styles.basket__title}>Минимальная стоимость заказа от <span>300&#8381;</span></p>
				<div className={styles.basket__item}>
					<div className={styles.basket__itemTitle}>
						<div><span>1</span></div>
						<h3>Ваш заказ</h3>
					</div>
					<div className={styles.basket__itemBody}>
						{
							basProducts?
							basProducts.map(e=>(
								<BasketItem
									id = {e.id}
									orderId={e.orderId}
									img={e.img}
									title={e.name}
									price={e.price}
									startPrice={e.startPrice}
									count={e.quantity}
									setTotalBasketPrice={setTotalBasketPrice}
									totalBasketPrice={totalBasketPrice}
								/>
							))
							:
							null
						}

					</div>
				</div> 

				<div className={styles.basket__itemBody}>
					<div className={styles.basket__itemTitle}>
						<p className={styles.promocode__title}>Промокод</p>
					</div>
					<div className={styles.basket__itemBodyElem}>
						<input 
							className={styles.inputKom} 
							type="text" 
							placeholder='Введите промокод'
							onChange={(e)=>setPromoCode(e.target.value)}
						/>
					</div>
					<button className={styles.promocode__btn} onClick={promocodeHandler}>Применить</button>
				</div>


				<div className={styles.basket__item}>
					<div className={styles.basket__itemTitle}>
						<div><span>2</span></div>
						<h3>Условия доставки</h3>
					</div>
					<div className={styles.basket__itemBody}>
						<div className={styles.basket__itemBodyElem}>
							<img className={styles.homeIcon} src={home} alt="icon" />
							<div className={styles.inputHomeWrap}>
								<input 
									className={styles.inputHome} 
									type="text" 
									value={streetAndHome}
									placeholder='Введите улицу и дом'
									onChange={streetAndHomeHandler}
								/>

									
									<div className={inputHomeAddresses? styles.inputHomeAddresses : styles.inputHomeAddresses__none}>
										{
											helpAddresses?
											helpAddresses.suggestions.map((elem)=>(
												<AddressHelper
													text={elem.value}
													onClick={()=>{
														setStreetAndHome(elem.value)
														setInputHomeAddresses(false)
													}}
												/>
											))
											:
											null
										}
									</div>
							</div>
						</div>
						<div className={styles.basket__itemBodyElem}>
							<input 
								className={styles.inputKv} 
								type="text" 
								placeholder='Кв/офис' 
								onChange={(e)=>setFlat(e.target.value)}
							/>
							<input 
								className={styles.inputOther} 
								type="text" 
								placeholder='Домофон'
								onChange={(e)=>setDoorphone(e.target.value)}
							/>
							<input 
								className={styles.inputOther} 
								type="text" 
								placeholder='Подъезд'
								onChange={(e)=>setEntrance(e.target.value)}
							/>
							<input 
								className={styles.inputOther} 
								type="text" 
								placeholder='Этаж'
								onChange={(e)=>setFloor(e.target.value)}
							/>
						</div>
						<div className={styles.basket__itemBodyElem}>
							<input 
								className={styles.inputKom} 
								type="text" 
								placeholder='Комментарий к заказу'
								onChange={(e)=>setComment(e.target.value)}
							/>
						</div>
					</div>
				</div>


				<div className={styles.basket__item}>
					<div className={styles.basket__itemTitle}>
						<div><span>3</span></div>
						<h3>Контактная информация</h3>
					</div>
					<div className={styles.basket__itemBody}>
						<div className={styles.phoneWrap}>
							<img className={styles.phoneIcon} src={blPhone} alt="icon" />
							<input 
								className={styles.inputPhone} 
								placeholder='Номер телефона'
								type="text" 
								onChange={phoneHandler}
							/>
						</div>
						<div className={styles.email}>
							<input 
								className={styles.inputEmail} 
								placeholder='E-mail'
								type="text" 
								onChange={emailHandler}
							/>
						</div>
						<div className={styles.conditions}>
							<label >
								<input 
									checked={conditionsChecked} 
									onChange={changeConditions} 
									className={styles.conditions__input} 
									type="checkbox" 
									name="conditions" 
								/>	
								<span className={styles.conditions__inputFake}></span>
								<span className={styles.conditions__text}>
									Я соглашаюсь с Условиями использования, Правилами обработки персональных данных
								</span>
							</label>
						</div>
					</div>
				</div>


			</div>
			<div className={styles.basket__total}> 
				<div className={styles.totalOff}>
					<span>Товар на сумму:</span><span>{ totalBasketPrice}&#8381;</span>
				</div>
				<div className={styles.totalOff}>
					<span>Доставка:</span><span>{deliveryPrice}&#8381;</span>
				</div>
				<div>
					<span>Сумма на оплату:</span><span className={styles.basket__totalPrice}>{orderPrice? alltotalBasketPrice: totalBasketPrice }&#8381;</span>
				</div>
				
				<button onClick={createOrder}>Оформить заказ</button>

			</div>

				<OrderModal
					orderModal={orderModal}
					onClick={()=> setOrderModal(!orderModal)}
				/>
		</div>
	);
}

export default Basket;


