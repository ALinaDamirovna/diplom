import { useEffect, useState } from 'react';
import { Routes, Route } from "react-router-dom";

import Header from './components/Header/Header';
import Footer from './components/Footer/Footer';
import Container from './components/Container/Container';

import Main from './pages/Main/Main';
import Contacts from './pages/Contacts/Contacts';
import Basket from './pages/Basket/Basket';
import Delivery from './pages/Delivery/Delivery';
import Auth from './pages/Auth/Auth';
import Registration from './pages/Registration/Registration';
import Account from './pages/Account/Account';
import PayStatus from './pages/PayStatus/PayStatus';
import PrivacyPolicy from './pages/PrivacyPolicy/PrivacyPolicy';
import PublicOffer from './pages/PublicOffer/PublicOffer';
import History from './pages/History/History';

import scrollUpImg from '../src/images/scroll-up.png'

import {Context} from './Context'
import styles from './App.module.scss';
import Recovery from './pages/Recovery/Recovery';



function App() {
	const [isAuth, setIsAuth] = useState(JSON.parse(localStorage.getItem('auth'))? localStorage.getItem('auth'): false)
	const [token, setToken] = useState(localStorage.getItem('token')? localStorage.getItem('token'):'')
	const [user, setUser] = useState()
	const [city, setCity] = useState("Ижевск")
	const [kladrId, setKladrId] = useState('1800000100000')
	const [scrollToTop, setScrollToTop] = useState(false)



	const [orderStatus, setOrderStatus] = useState(Boolean)
	const [basketCount, setBasketCount] = useState(0)
	const [products, setProducts] = useState([])
	const [basketProducts, setBasketProducts] = useState(JSON.parse(localStorage.getItem('basketProducts'))? JSON.parse(localStorage.getItem('basketProducts')):[])



	const requestOptions = {
		method: 'GET',
		redirect: 'follow'
	 };
	async function getProducts (){
		await fetch("https://lavash.endlessmind.space/api/products", requestOptions)
		.then(response => response.json())
		.then(result => {
			let arr=[]
			for(let i = 1; i < 15; i++){
				result.map(e=>{
					if(e.category == i){
						arr.push(e)
					}
				})
			}
			setProducts(arr)
		})
		.catch(error => console.log('error', error));
	}
	const sendToLocalStorage = () =>{
		localStorage.setItem('basketProducts', JSON.stringify(basketProducts))
	}


	useEffect(() => {
		window.addEventListener("scroll", ()=>{
			if(window.scrollY > 1500){
				setScrollToTop(true)
			}else{
				setScrollToTop(false)
			}
		})
	}, []);


	const scrollUp =()=>{
		window.scrollTo({
			top: 0,
			behavior: "smooth"
		})
	}





	useEffect(() => {
		getProducts()
	}, []);


	useEffect(() => {
		localStorage.setItem('auth', isAuth)
	}, [isAuth]);

	useEffect(() => {
		sendToLocalStorage()
	}, [basketProducts]);

  return (
	<Context.Provider value={
		{
			products,
			setProducts,
			basketProducts,
			setBasketProducts,
			basketCount,
			setBasketCount,
			orderStatus,
			setOrderStatus,
			isAuth,
			setIsAuth,
			token,
			setToken,
			user,
			setUser,
			city,
			setCity,
			kladrId,
			setKladrId
		}
	}
	
	>
		<div className={styles.App}>
			<Header/>
				<Container>
					<Routes>
						<Route path="/" element={<Main/>} />
						<Route path="delivery" element={<Delivery/>} />	
						<Route path="contacts" element={<Contacts/>} />	
						<Route path="basket" element={<Basket/>} />
						<Route path="registration" element={<Registration/>} />
						<Route path="account" element={<Account/>} />
						<Route path="payStatus" element={<PayStatus/>} />
						<Route path="privacyPolicy" element={<PrivacyPolicy/>} />
						<Route path="publicOffer" element={<PublicOffer/>} />
						<Route path="account/history" element={<History/>} />
						<Route path="account/recovery" element={<Recovery/>} />
					</Routes>

				</Container>


				{
					scrollToTop?
						<div onClick={scrollUp} className={styles.scroll_up}>
							<img className={styles.scroll_upImg} src={scrollUpImg} alt="" />
						</div>
					:
					null
				}
			<Footer/>
		</div>
	</Context.Provider>
  );
}

export default App;
