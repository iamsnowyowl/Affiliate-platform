import React, { Component, Fragment } from "react";
import { createBottomTabNavigator, createAppContainer } from "react-navigation";
import { connect } from "react-redux";
import Icon from "react-native-vector-icons/MaterialCommunityIcons";
import Home from "../menu/home/Home";
import Discover from "../menu/discover/Discover";
import Cart from "../menu/cart/Cart";
import Profile from "../menu/profile/Profile";
import { color } from "../../styles/color";

const getTabBarIcon = (navigation, focused, tintColor) => {
  const { routeName } = navigation.state;
  let IconComponent = Icon;
  let iconName;
  if (routeName === "Home") {
    iconName = `home${focused ? "" : "-outline"}`;
  }
  //  else if (routeName === "Discover") {
  //   iconName = `clipboard${focused ? "" : "-outline"}`;
  // } else if (routeName === "Cart") {
  //   iconName = `cart${focused ? "" : "-outline"}`;
  // }
  else if (routeName === "Profile") {
    iconName = `account${focused ? "" : "-outline"}`;
  }

  return <IconComponent name={iconName} size={25} color={tintColor} />;
};

const TabNavigator = createAppContainer(
  createBottomTabNavigator(
    {
      Home,
      // Discover,
      // Cart,
      Profile
    },
    {
      defaultNavigationOptions: ({ navigation }) => ({
        tabBarIcon: ({ focused, tintColor }) =>
          getTabBarIcon(navigation, focused, tintColor)
      }),
      tabBarOptions: {
        activeTintColor: color.green,
        inactiveTintColor: "#a7adba"
      }
    }
  )
);

const mapStateToProps = state => ({});

const mapDispatchToProps = dispatch => ({});

export default connect(
  mapStateToProps,
  mapDispatchToProps
)(TabNavigator);
