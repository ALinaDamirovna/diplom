import React, {useState, useEffect, useContext} from 'react';
import {Link} from 'react-router-dom'
//IMAGE
import user from '../../images/user.svg'
import chese from '../../images/chesse.png'
import salat from '../../images/salat.png'
import pomidor from '../../images/pomidor.png'
import passImg from '../../images/pass.png'
import unpassImg from '../../images/unpass.png'
//STYLE
import styles from './Recovery.module.scss'




function Registration(props) {
	const [phone, setPhone] = useState('')
	const [correctPhone, setCorrectPhone] = useState(false)



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
	

	const raw = {
		"phone": phone
	};

	const requestOptions = {
		method: 'POST',
		headers: {
			'Accept': 'application/json',
			'Content-Type': 'application/json'
		 },
		body: JSON.stringify(raw),
		redirect: 'follow'
	};
	async function changePass(){
		fetch("https://lavash.endlessmind.space/api/recovery", requestOptions)
		.then(response => response.json())
		.then(result => {
			console.log(result)
			if(result.status){
				alert(result.message)
			}else{
				alert(result.message)
			}
		})
		.catch(error =>{
			console.log('error', error)
			alert(error)
		});
	}


	const recoveryHandler = () => {
		if(correctPhone){
			changePass()
		}else{
			alert("Введите корректный номер телефона!")
		}
	}

	return (
		<div className={styles.authWrap}>
			<img  className={styles.salat} src={salat} alt="" />
			<img className={styles.pomidor} src={pomidor} alt="" />
			<img className={styles.chese} src={chese} alt="" />
			<div className={styles.auth}>
				<div>
					<h2 className={styles.auth__title}>Восстановление пароля</h2>
				</div>

				
				<input onChange={phoneHandler} className={styles.auth__revPhone} placeholder="Номер телефона" type="text" />
					
				<div class={styles.auth__btnWrap}>
					<button onClick={recoveryHandler} class={styles.auth__btnEnter}>Восстановить</button>
					<Link to="/account"><button class={styles.auth__btnReg}>Назад</button></Link>
				</div>
			</div>
		</div>
	);
}

export default Registration;