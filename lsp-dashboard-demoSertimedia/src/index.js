import React from 'react';
import ReactDOM from 'react-dom';
import { BrowserRouter } from 'react-router-dom';
import 'bootstrap/dist/css/bootstrap.min.css';
import App from './App';
import './css/index.css';
import './css/widget.css';
// import registerServiceWorker from './registerServiceWorker';

require('react-big-calendar/lib/css/react-big-calendar.css');

// import createBrowserHistory from 'history/createBrowserHistory';
// const history = createBrowserHistory();
// if(sessionStorage.getItem("logged_in")){
//   history.push('/dashboard')
// }
// else{
//   history.push('/login')
// }

ReactDOM.render(
  <BrowserRouter>
    <App />
  </BrowserRouter>,
  document.getElementById('root')
);
// registerServiceWorker();
// disable ServiceWorker
