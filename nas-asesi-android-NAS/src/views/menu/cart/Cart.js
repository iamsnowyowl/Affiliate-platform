import React, { Component, Fragment } from "react";
import {
  createMaterialTopTabNavigator,
  createAppContainer
} from "react-navigation";
import { color } from "../../../styles/color";
import { connect } from "react-redux";
import actions from "../../../actions";
import Header from "../../../components/header/header";
import TabCurrentOrder from "../cart/currentOrder/currentOrder";
import TabInvoice from "../cart/invoice/invoice";
import TabHistory from "../cart/history/history";
import constants from "../../../constants/constants";

const TabNavigator = createMaterialTopTabNavigator(
  {
    CurrentOrder: {
      screen: TabCurrentOrder,
      navigationOptions: {
        tabBarLabel: constants.MULTILANGUAGE("en").order
      }
    },
    Invoice: {
      screen: TabInvoice,
      navigationOptions: {
        tabBarLabel: constants.MULTILANGUAGE("en").invoice
      }
    },
    History: {
      screen: TabHistory,
      navigationOptions: {
        tabBarLabel: constants.MULTILANGUAGE("en").history
      }
    }
  },
  {
    animationEnabled: true,
    swipeEnabled: true,
    initialRouteName: "CurrentOrder",
    tabBarOptions: {
      upperCaseLabel: false,
      activeTintColor: "black",
      inactiveTintColor: "gray",
      showLabel: true,
      indicatorStyle: {
        backgroundColor: color.lightGreen
      },
      style: {
        backgroundColor: "white"
      }
    }
  }
);

const TabContainer = createAppContainer(TabNavigator);

class Cart extends Component {
  static navigationOptions = {
    tabBarLabel: constants.MULTILANGUAGE("en").cart
  };
  componentDidMount() {
    this.props.cart.gotoCart
      ? this.props.gotoCart(false, response => response)
      : null;
  }

  render() {
    return (
      <Fragment>
        <Header
          headerColor="white"
          title={constants.APP_NAME}
          rightIconName="bell"
        />
        <TabContainer screenProps={this.props.navigation} />
      </Fragment>
    );
  }
}

const mapStateToProps = state => ({
  cart: state.cart,
  settings: state.settings
});

const mapDispatchToProps = dispatch => ({
  gotoCart: (data, callback) =>
    dispatch(actions.actionsAPI.cart.gotoCart(data, callback))
});

export default connect(
  mapStateToProps,
  mapDispatchToProps
)(Cart);
