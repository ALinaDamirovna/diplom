import React, {useState, useEffect, useContext} from 'react';
import styles from './Menu.module.scss'
import { Context } from '../../Context';



function Menu(props) {
	const [menuItems, setMenuItems] = useState([])
	const {setProducts} = useContext(Context);


	const requestOptions = {
		method: 'GET',
		redirect: 'follow'
	 };


	const setCategories = (e) =>{
		menuItems.map(elem=>{
			elem.active = false;
		})
		menuItems.map(elem=>{
			if(e.target.id == elem.id){
				elem.active = true;
			}
		})

		async function getCategories (){
			await fetch(`https://lavash.endlessmind.space/api/products?category=${e.target.id}`, requestOptions)
			.then(response => response.json())
			.then(result => {
				console.log(result)
				setProducts(result)
			})
			.catch(error => console.log('error', error));
		}
	
		getCategories()
	}


	async function getMenuItems (){
		await fetch("https://lavash.endlessmind.space/api/categories", requestOptions)
		.then(response => response.json())
		.then(result => {
			let items = [];
			result.map((elem)=>{
				items.push( 
					{
						"id": elem.id,
						"name": elem.name,
						"active": false
					}
				)
			})
			setMenuItems(items) 
		})
		.catch(error => console.log('error', error));
	}

	useEffect(() => {
		getMenuItems()
	}, []);
	console.log(menuItems)


	return (
			<ul className={styles.menu}>
				{
					menuItems.map(elem=>(
						<li 
							active ={elem.active}
							id={elem.id} 
							onClick={setCategories} 
							className={elem.active? styles.active__item: styles.menu__item}
						>
							{elem.name}
						</li>
					))
				}
        	</ul>
	);
}

export default Menu;


