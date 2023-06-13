import React, { useContext, useEffect, useState } from 'react';
import { Link } from 'react-router-dom';


//IMAGES
import salat from '../../images/salat.png'
import pomidor from '../../images/pomidor.png'
import passImg from '../../images/pass.png'
import unpassImg from '../../images/unpass.png'
import home from '../../images/home.svg'
import userIcon from '../../images/user.svg'
import blPhone from '../../images/bl-phone.svg'
import chesse from '../../images/chesse.png'


import { Context } from './../../Context';
import styles from './Account.module.scss'


function Account(props) {
	const {user, setUser, token, setToken, isAuth, setIsAuth} = useContext(Context)
	const [auth, setAuth] = useState()
	//EMAIL
	const [email, setEmail] = useState('')
	const [correctEmail, setCorrectEmail] = useState(false)
	//PHONE
	const [phone, setPhone] = useState('')
	const [correctPhone, setCorrectPhone] = useState(false)
	//PASSWORD
	const [password, setPassword] = useState('')
	const [correctPass, setCorrectPass] = useState(false)
	const [passVisible, setPassVisible] = useState(false)
	const [passType, setPassType] = useState('password')


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

	const passHandler= (e)=>{
		setPassword(e.target.value)
		const re = /^(?=.*[a-z])(?=.*[0-9]).{6,}/i;
		if(!re.test(String(e.target.value))){
			e.target.style="border: 1px solid #c43939;"
			setCorrectPass(false)
		}else{
			e.target.style="border: 1px solid #B9B9B9;"
			setCorrectPass(true)
		}

  	}
	const passVisibleHandler = ()=>{
		setPassVisible(!passVisible)
		if(!passVisible){
			setPassType("text")
		}else{
			setPassType("password")
		}
	}

	const requestOptionsforlogin = {
		method: 'POST',
		headers: {
			'Accept': 'application/json',
			'Content-Type': 'application/json'
		 },
		body: JSON.stringify(auth),
		redirect: 'follow'
	};
	
	async function authorization(){
		fetch("https://lavash.endlessmind.space/api/login", requestOptionsforlogin)
			.then(response => response.json())
			.then(result => {
				if(result.token){
					localStorage.setItem("token", result.token)
					setToken(result.token)
					setIsAuth(true)
				}else{
					alert(result.message)
				}
			})
			.catch(error => {
				console.log('error', error)
				alert(error)
			});
	}


	const authHandler= (e)=>{
		if(!correctPass){
			return alert("Введите корректный пароль")
		}else{
			console.log(auth)
			authorization()
		}
  	}




	const outHandler =()=>{
		setIsAuth(false)
		localStorage.setItem('token', '')
	}

	const requestOptions = {
		method: 'GET',
		headers: {
			'Accept': 'application/json',
			'Content-Type': 'application/json',
			'Authorization': 'Bearer ' + token
		 },
		redirect: 'follow'
	 };
	async function getUser(){
		await fetch("https://lavash.endlessmind.space/api/user", requestOptions)
				.then(response => response.json())
				.then(result => {
					console.log(result)
					setUser(result)
				})
				.catch(error => console.log('Error', error));
	}

	useEffect(()=>{
		if(isAuth){
			getUser()
		}
	},[isAuth])

	useEffect(()=>{
		setAuth({
			"phone": phone,
			"password": password,
		})
	},[phone, password])
	return (

		!isAuth?
			<div className={styles.authWrap}>
				<img  className={styles.ausalat} src={salat} alt="" />
				<img className={styles.aupomidor} src={pomidor} alt="" />
				<img className={styles.auchese} src={chesse} alt="" />
				<div className={styles.auth}>
					<div>
						<img src={userIcon} alt="icon" />
						<h2 className={styles.auth__title}>Авторизация</h2>
					</div>
					<input onChange={phoneHandler} className={styles.auth__revPhone} placeholder="Номер телефона" type="text" />
					<div className={styles.passVisible}>
						<input onChange={passHandler} className={styles.auth__revPass} placeholder="Пароль" type={passType} />
						{
							passVisible?
								<img onClick={passVisibleHandler} className={styles.passVisible__img} src={unpassImg} alt="" />
							:
							<img onClick={passVisibleHandler} className={styles.passVisible__img} src={passImg} alt="" />
						}
					</div>
					<Link to="recovery">Восстановить пароль</Link>
					<div class={styles.auth__btnWrap}>
						<button onClick={authHandler} class={styles.auth__btnEnter}>Войти</button>
						<Link to="/registration"><button class={styles.auth__btnReg}>Регистрация</button></Link>
					</div>
				</div>
			</div>
		:

			<div className={styles.account}>
				<img className={styles.chesse} src={chesse} alt="img" />
				<img className={styles.pomidor} src={pomidor} alt="img" />
				<img className={styles.salat} src={salat} alt="img" />
				<div className={styles.account__main}>
					<div className={styles.account__item}>
						<div className={styles.account__itemTitleWrap}>
							<img className={styles.userIcon} src={userIcon} alt="icon" />
							<h2 className={styles.account_itemTitle}>Личный кабинет</h2>
						</div>
						{
							user?
							<div className={styles.account__itemBody}>
								<p className={styles.account__info}>{user.name}</p>
								<p className={styles.account__info}>{user.phone}</p>
							</div>
							:
							null
						}
					</div> 

					<div className={styles.account__item}>
						<div className={styles.account__itemTitleWrap}>
							<img className={styles.homeIcon} src={home} alt="icon" />
							<h2 className={styles.account_itemTitle}>Адрес доставки</h2>
						</div>
						<div className={styles.account__itemBody}>
							<div className={styles.account__itemBodyElem}>
								<input 
									className={styles.inputHome} 
									type="text" 
									placeholder='Введите улицу и дом'
								/>
							</div>
							<div className={styles.account__itemBodyElem}>
								<input 
									className={styles.inputKv} 
									type="text" 
									placeholder='Кв/офис' 
								/>
								<input 
									className={styles.inputOther} 
									type="text" 
									placeholder='Домофон'
								/>
								<input 
									className={styles.inputOther} 
									type="text" 
									placeholder='Подъезд'
								/>
								<input 
									className={styles.inputOther} 
									type="text" 
									placeholder='Этаж'
								/>
							</div>
							<div className={styles.account__itemBodyElem}>
								<input 
									className={styles.inputKoment} 
									type="text" 
									placeholder='Комментарий к заказу'
								/>
							</div>
						</div>
					</div> 

					

				</div>
				<div className={styles.account__total}> 
					<Link to="history">История заказов</Link>
					<div className={styles.outBtn} onClick={outHandler}>Выход</div>
				</div>
			</div>
	);
}

export default Account;