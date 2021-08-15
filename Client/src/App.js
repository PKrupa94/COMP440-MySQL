import React from 'react';
import 'bootstrap/dist/css/bootstrap.min.css';
import './App.css';
import { BrowserRouter as Router, Switch, Route } from 'react-router-dom'
import Login from './components/User/Login/login.component'
import SignUp from './components/User/Register/registration.component'
import Dashboard from './components/User/Dashboard/dashboard.component'
import CreateNewBlog from './components/Blog/NewBlog/createnewblog.component'
import BlogDetail from './components/Blog/Detailblog/blogdetails.component'
import ListUserBlog from './components/Blog/Ownblog/listuserblog.controller'
import ListFollowers from './components/User/Follower/followerlist.controller'
import UsersList from './components/User/UsersList/userslist.controller'

function App() {

  return (
    <Router>
      <div className="App">
        <div className="auth-wrapper">
          <Switch>
            <Route exact path='/' component={Dashboard} />
            <Route path="/sign-in" component={Login} />
            <Route path="/sign-up" component={SignUp} />
            <Route path='/newblog' component={CreateNewBlog} />
            <Route path='/detailblog' component={BlogDetail} />
            <Route path='/myblog' component={ListUserBlog} />
            <Route path='/followers' component={ListFollowers} />
            <Route path='/userslist' component={UsersList} />
          </Switch>
        </div>
      </div>
    </Router>
  );
}

export default App;
