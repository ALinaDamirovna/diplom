import React, {useEffect, useState} from 'react';
import styles from './MoreItem.module.scss'


function MoreItem(props) {

	const [count, setCount] = useState(0)
	const [additions, setAdditions] = useState()


	const countUpHandler = (e) =>{
		setCount(count + 1)

		setAdditions({
			"id": props.id,
			"quantity": count + 1,
			"price": props.price * (count + 1),
			"name": props.moreTitle
		})

	}
	const countDownHandler = (e) =>{
		if(count > 0){
			setCount(count - 1);

			setAdditions({
				"id": props.id,
				"quantity": count - 1,
				"price": props.price  * (count - 1) ,
				"name": props.moreTitle
			})
		}
	}
	
	useEffect(e=>{
		props.getAdditions(additions)
	},[additions])


	return (
		<div className={styles.moreItem}>
			<div  className={styles.addPrice}>+{props.price}&#8381;</div>
			<img className={styles.moreItem__img} src={props.moreImg} alt="img" />
			<span>{props.moreTitle}</span>
			<div className={styles.countItem}>
				<span  onClick={countDownHandler} className={styles.countDown}>-</span>
				<span>{count}</span>
				<span  onClick={countUpHandler} className={styles.countUp}>+</span>
			</div>
		</div>
	);
}

export default MoreItem;