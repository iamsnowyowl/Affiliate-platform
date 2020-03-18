import React, { Component } from "react";
import { View, Text, TouchableHighlight } from "react-native";
import LinearGradient from "react-native-linear-gradient";
import globalStyle from "../../styles/index";
import { color } from "../../styles/color";

type Props = {
  title: String,
  titleSize: Integer,
  titleColor: String,
  border: String,
  onPressed: any
};

export default class Button extends Component<Props> {
  render() {
    const { title, titleSize, titleColor, border, onPressed } = this.props;
    return (
      <TouchableHighlight onPress={onPressed}>
        <LinearGradient
          start={{ x: 0, y: 0 }}
          end={{ x: 1, y: 0 }}
          colors={[color.green, color.lightGreen]}
          style={border ? globalStyle.borderButton : globalStyle.button}
        >
          <Text
            style={globalStyle.textInButton(titleSize, "normal", titleColor)}
          >
            {title}
          </Text>
        </LinearGradient>
      </TouchableHighlight>
    );
  }
}
