import React, { useContext, useState } from 'react';
import AuthContext from '../../../../AuthContext'
import axios from 'axios';
import Alert from '../../../Alert/alert.component';
import classes from './Navigation.module.css';

const Navigation = () => {
  const ctxt = useContext(AuthContext)
  const [stateDatabaseInitialize, setDatabaseInitialize] = useState('false')

  const initDatabaseHandler = () => {
    // <Alert message="Database is successfully Initialize" />
    //need to set up api call
    // axios.get('').then(response => {

    // }).catch(error => {

    // })


    console.log('button click')
  }

  return (
    <nav className={classes.nav}>
      <ul>
        {ctxt.isLoggedIn && (
          <li>
            <a href="/newblog">Create New Article</a>
          </li>
        )}
        {ctxt.isLoggedIn && (
          <li>
            {/* <a href="/">Initialize  Database</a> */}
            <button onClick={initDatabaseHandler}>Initialize  Database</button>
          </li>
        )}
        {ctxt.isLoggedIn && (
          <li>
            <button onClick={ctxt.onLogout}>Logout</button>
          </li>
        )}
      </ul>
    </nav>
  )
};

export default Navigation;
