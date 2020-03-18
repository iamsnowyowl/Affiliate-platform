import React, { Component } from "react";
import { View, Text, TextInput, StyleSheet, Dimensions } from "react-native";
import Icon from "react-native-vector-icons/FontAwesome";
import { color } from "../../styles/color";
const { width, height } = Dimensions.get("window");

type Props = {
  type?: String,
  value?: any,
  placeholder?: String,
  onChangeText?: any,
  isPassword?: false,
  keyboardType?: String
};
export default class FormWithIcon extends Component<Props> {
  constructor(props) {
    super(props);
    this.state = {};
  }

  render() {
    const {
      type,
      value,
      placeholder,
      onChangeText,
      isPassword,
      keyboardType
    } = this.props;
    return (
      <View>
        <View style={styles.textContainer}>
          <Icon
            style={{ alignSelf: "center" }}
            name="search"
            size={15}
            color={color.darkGrey}
          />
          <View style={{ width: 10 }} />
          <TextInput
            ref={type}
            value={value}
            onChangeText={value => onChangeText(type, value)}
            style={styles.textInput}
            keyboardType={keyboardType}
            secureTextEntry={isPassword}
            // secureTextEntry={this.secureText(isPassword)}
            placeholderTextColor={color.darkGrey}
            placeholder={placeholder}
          />
        </View>
      </View>
    );
  }
}

const styles = StyleSheet.create({
  textInput: {
    height: 38,
    width: width,
    justifyContent: "center",
    color: color.black,
    fontWeight: "bold"
  },
  textContainer: {
    paddingHorizontal: 8,
    borderRadius: 8,
    marginHorizontal: 10,
    marginTop: 10,
    borderWidth: 1,
    elevation: 8,
    backgroundColor: "white",
    flexDirection: "row",
    borderColor: color.darkGrey
  }
});
