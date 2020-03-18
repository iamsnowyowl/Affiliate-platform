import React, { Component } from "react";
import { HashRouter, Route, Switch, BrowserRouter } from "react-router-dom";

// Styles
import "flag-icon-css/css/flag-icon.min.css";
import "font-awesome/css/font-awesome.min.css";
import "simple-line-icons/css/simple-line-icons.css";
import "./scss/style.css";
import "./css/loaderComponent.css";

// Containers
import DefaultLayout from "./containers/DefaultLayout/DefaultLayout";
import { Page404 } from "./views/Pages";
import { NewsWebViews } from "./views/Pages";
import Login from "./containers/Login";
// import { NotificationManager } from 'react-notifications';
import firebase from "firebase/app";
import "firebase/messaging";
import { configFCM } from "./containers/Helpers/FCMConfig";
import Axios from "axios";

firebase.initializeApp(configFCM);

class App extends Component {
  constructor(props) {
    super(props);
    this.state = {
      logged_id: undefined
    };
  }

  componentDidMount() {
    return Axios.interceptors.response.use(
      response => {
        console.log("response", response);
        return response;
      },
      error => {
        console.log("error", error);
        return Promise.reject(error);
      }
    );
  }

  render() {
    return (
      <BrowserRouter>
        <Switch>
          <Route path="/404" component={Page404} />
          <Route path="/articles/:article_id" component={NewsWebViews} />
          <Route path="/login" name="Login" component={Login} />
          <Route path="/" name="Home" component={DefaultLayout} />
        </Switch>
      </BrowserRouter>
    );
  }
}

export default App;
