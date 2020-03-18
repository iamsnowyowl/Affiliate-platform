import React, { Component } from "react";
import { View, Text, TouchableOpacity } from "react-native";
import Icon from "react-native-vector-icons/FontAwesome";
import { color } from "../../styles/color";

type Props = {
  selected: boolean,
  color: any,
  size: any,
  title: String
};
export default class RadioButton extends Component<Props> {
  constructor(props) {
    super(props);
    this.state = {};
  }

  render() {
    const { selected, color, size, title } = this.props;
    return (
      <View
        style={{
          flexDirection: "row",
          justifyContent: "center",
          alignItems: "center"
        }}
      >
        <Icon
          name={selected == true ? "dot-circle-o" : "circle-o"}
          color={color}
          size={size}
        />
        <View style={{ width: 10 }} />
        <Text style={{ color: "black", fontSize: 16 }}>{title}</Text>
      </View>
    );
  }
}
