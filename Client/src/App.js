import React from 'react';
import 'bootstrap/dist/css/bootstrap.min.css';
import './App.css';
import { BrowserRouter as Router, Switch, Route } from 'react-router-dom'
import Login from './components/User/Login/login.component'
import SignUp from './components/User/Register/registration.component'
import Dashboard from './components/User/Dashboard/dashboard.component'
import CreateNewBlog from './components/Blog/NewBlog/createnewblog.component'
import BlogDetail from './components/Blog/Detailblog/blogdetails.component'

function App() {

  return (
    <Router>
      <div className="App">
        <div className="auth-wrapper">
          <Switch>
            <Route exact path='/home' component={Dashboard} />
            <Route path="/sign-in" component={Login} />
            <Route path="/sign-up" component={SignUp} />
            <Route path='/newblog' component={CreateNewBlog} />
            <Route path='/detailblog' component={BlogDetail} />
          </Switch>
        </div>
      </div>
    </Router>
  );
}

export default App;
