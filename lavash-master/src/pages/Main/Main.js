import React, { useContext} from 'react';
import Card from '../../components/Card/Card'
import pomidor from '../../images/pomidor.png'
import ogurec from '../../images/ogurec.png'
import Menu from '../../components/Menu/Menu'
import { Context } from '../../Context'
import styles from './Main.module.scss'

function Main(props) {
	const {products} = useContext(Context)
	
	return (
		<div>
			<img className={styles.pomidor} src={pomidor} alt="img" />
      	<img className={styles.ogurec} src={ogurec} alt="img" />

			<Menu/>

      	<div className={styles.menuWrapper}>

				{
					products.map(elem=>(
						<Card 
							id = {elem.id}
							img={elem.photo} 
							title={elem.name} 
							text={elem.desc}
							price={elem.price}
						/>
					))
				}
          	
        	</div>
		</div>
	);
}

export default Main;