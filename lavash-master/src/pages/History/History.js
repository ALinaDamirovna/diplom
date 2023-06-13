import React, {useContext, useEffect, useState} from 'react';
import { Link } from 'react-router-dom';
import { Context } from '../../Context';

//STYLE
import styles from './History.module.scss'




function History(props) {
	const [addresses, setAddresses] = useState()
	const {token} = useContext(Context)
	

	const requestOptions = {
		method: 'GET',
		headers: {
			'Accept': 'application/json',
			'Content-Type': 'application/json',
			'Authorization': 'Bearer ' + token
		 },
		redirect: 'follow'
	 };

	async function getAddreses(){
		await fetch("https://lavash.endlessmind.space/api/orders", requestOptions)
			.then(response => response.json())
			.then(result => {
				console.log(result)
				setAddresses(result)
			})
			.catch(error => console.log('error', error));
	
	}

	useEffect(() => {
		getAddreses()
	},[])


	return (
		<div className={styles.authWrap}>
			<div className={styles.auth}>
				<Link className={styles.auth__backLink} to="/account"> &#8592;	 Назад </Link>
				{
					addresses ?
					<div>
						
						<table className={styles.table}>
							<thead className={styles.table__title}>
								<td  className={styles.table__num}>
									#
								</td>
								<td className={styles.table__status}>
									Статус
								</td>
								<td className={styles.table__date}>
									Дата
								</td>
								<td className={styles.table__sum}>
									Сумма
								</td>
							</thead>

							<tbody>
								{
									addresses.map(e=>(
										<tr  className={styles.table__info}>
											<td className={styles.table__num}>
												{e.id}
											</td>
											<td className={styles.table__status}>
												{e.phone}
											</td>
											<td className={styles.table__date}>
												{e.created_at}
											</td>
											<td className={styles.table__sum}>
												{e.total_price}
											</td>
										</tr>
									))
								}
							</tbody>
						</table>
					</div>
					:
					null
				}

			</div>
		</div>
	);
}

export default History;