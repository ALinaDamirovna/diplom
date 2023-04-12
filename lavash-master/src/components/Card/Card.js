import React, {useState} from 'react';
import Modal from '../Modal/Modal';
import styles from './Card.module.scss'
import { Context } from '../../Context';

function Card(props) {
	const [modal, setModal] = useState(false);


	
	const modalHandler = () =>{
		setModal(!modal)
	}
	const closeModal = () =>{
		setModal(!modal)
	}
	

	return (
		<div  className={styles.card}>
			<img className={styles.card__img} src={props.img} alt="img" />
			<div className={styles.card__wrap}>
				<div>
					<h3 className={styles.card__title}>{props.title}</h3>
					<p className={styles.card__text}>{props.text}</p>

				</div>

				<div  className={styles.contentGroup}>
					<div className={styles.card__group}>
						<span className={styles.card__price}>{props.price}&#8381;</span>
					</div>
					<button onClick={modalHandler} className={styles.card__btn}>Заказать</button>
				</div>

			</div>

					<Modal
						id={props.id}
						onClick={closeModal}
						modal={modal}
						price={props.price}
					/>
			
		</div>
	);
}

export default Card;