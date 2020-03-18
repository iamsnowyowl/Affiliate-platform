/**
 * Sample React Native App
 * https://github.com/facebook/react-native
 *
 * @format
 * @flow
 */

import React, { Component } from "react";
import AsyncStorage from "@react-native-community/async-storage";
import { Provider, connect } from "react-redux";
import { compose, applyMiddleware, createStore } from "redux";
import { persistStore, autoRehydrate } from "redux-persist";
import logger from "redux-logger";
import thunk from "redux-thunk";
import AppNavigator from "./src/navigations/navigation";

import rootReducers from "./src/reducers";
import config from "./src/config/config";
import * as Sentry from "@sentry/react-native";

const store = createStore(
  rootReducers,
  compose(
    config.environtment == "production"
      ? applyMiddleware(thunk)
      : applyMiddleware(thunk, logger),
    autoRehydrate()
  )
);

persistStore(store, { storage: AsyncStorage });

export default class App extends Component {
  render() {
    Sentry.init({
      dsn: "https://f606af80d39b4751a03b8cdf0ee00ed4@sentry.io/1844217"
    });
    return (
      <Provider store={store}>
        <AppNavigator />
      </Provider>
    );
  }
}
