import React, {useContext, useEffect, useState} from 'react';
import { Link } from 'react-router-dom';
//IMAGES
import user from '../../images/user.svg'
import chese from '../../images/chesse.png'
import salat from '../../images/salat.png'
import pomidor from '../../images/pomidor.png'
import passImg from '../../images/pass.png'
import unpassImg from '../../images/unpass.png'
//STYLE
import styles from './Auth.module.scss'
//CONTEXT
import { Context } from './../../Context';
import Account from '../Account/Account';



function Auth(props) {
	//AUTH
	const {token, setToken, isAuth, setIsAuth} = useContext(Context)
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

	const requestOptions = {
		method: 'POST',
		headers: {
			'Accept': 'application/json',
			'Content-Type': 'application/json'
		 },
		body: JSON.stringify(auth),
		redirect: 'follow'
	};
	
	async function authorization(){
		fetch("https://lavash.endlessmind.space/api/login", requestOptions)
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
		}
		if(phone){
			console.log(auth)
			authorization()
		}else{
			return alert("Введите корректный номер телефона")
		}
  	}


	

	useEffect(()=>{
		setAuth({
			"phone": phone,
			"password": password,
		})
	},[phone, password])

	return (
		!isAuth?
		<div className={styles.authWrap}>
			<img  className={styles.salat} src={salat} alt="" />
			<img className={styles.pomidor} src={pomidor} alt="" />
			<img className={styles.chese} src={chese} alt="" />
			<div className={styles.auth}>
				<div>
					<img src={user} alt="icon" />
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
				<a href="">Восстановить пароль</a>
				<div class={styles.auth__btnWrap}>
					<button onClick={authHandler} class={styles.auth__btnEnter}>Войти</button>
					<Link to="/registration"><button class={styles.auth__btnReg}>Регистрация</button></Link>
				</div>
			</div>
		</div>
		:
		<Account/>
	);
}

export default Auth;