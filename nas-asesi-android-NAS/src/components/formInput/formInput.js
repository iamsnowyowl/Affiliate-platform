import React, { Component } from "react";
import { View, TextInput, StyleSheet, Text } from "react-native";
import { color } from "../../styles/color";

type Props = {
  type?: String,
  value?: any,
  placeholder?: String,
  onChangeText?: any,
  isPassword?: false,
  keyboardType?: String,
  required?: Boolean
};
export default class FormInput extends Component<Props> {
  constructor(props) {
    super(props);
    this.state = {};
  }

  secureText(value) {
    if (value == true) {
      return this.state.isSecret;
    } else {
      return false;
    }
  }

  render() {
    const {
      type,
      value,
      placeholder,
      onChangeText,
      isPassword,
      required,
      keyboardType
    } = this.props;
    return (
      <View>
        <View style={{ flexDirection: "row" }}>
          <Text style={{ fontWeight: "bold" }}>{placeholder}</Text>
          {required == true ? <Text style={styles.redStar}>*</Text> : null}
        </View>
        <View style={styles.textContainer}>
          <TextInput
            allowFontScaling={true}
            autoCapitalize="none"
            ref={type}
            value={value}
            onChangeText={value => onChangeText(type, value)}
            style={styles.textInput}
            keyboardType={keyboardType}
            secureTextEntry={isPassword}
            // secureTextEntry={this.secureText(isPassword)}
            placeholderTextColor={color.greyPlaceholder}
            placeholder={placeholder}
          />
        </View>
      </View>
    );
  }
}

const styles = StyleSheet.create({
  textInput: {
    justifyContent: "center",
    color: color.black
    // fontSize: 15,
    // fontWeight: "bold"
  },
  textContainer: {
    // elevation: 5,
    backgroundColor: "white",
    paddingHorizontal: 3,
    borderRadius: 5,
    borderWidth: 1,
    borderColor: color.darkGrey
  },
  redStar: {
    position: "absolute",
    right: 10,
    color: "red"
  }
});
