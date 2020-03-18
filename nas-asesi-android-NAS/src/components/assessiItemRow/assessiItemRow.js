import React, { Component } from "react";
import { View, Text, Dimensions, StyleSheet } from "react-native";
import { color } from "../../styles/color";
import Icon from "react-native-vector-icons/AntDesign";
import constants from "../../constants/constants";

const { width, height } = Dimensions.get("window");

type Props = {
  isLastIndex: boolean,
  name: string,
  company: string
};
export default class AssessItemRow extends Component<Props> {
  constructor(props) {
    super(props);
    this.state = { bahasa: "id" };
  }

  render() {
    const { isLastIndex, name, company, children } = this.props;
    if (isLastIndex == true) {
      return (
        <View
          style={{
            flexDirection: "row",
            width: width - 40,
            paddingVertical: 10
          }}
        >
          <View
            style={{
              flex: 0.8,
              justifyContent: "center"
            }}
          >
            <Icon name={"pluscircleo"} color={color.green} size={45} />
          </View>
          <View
            style={{
              flex: 4,
              justifyContent: "center"
            }}
          >
            <Text numberOfLines={2} style={styles.text}>
              {constants.MULTILANGUAGE(this.state.bahasa).add_other_assessee}
            </Text>
          </View>
        </View>
      );
    } else {
      return (
        <View
          style={{
            flexDirection: "row",
            width: width - 40,
            paddingVertical: 10
          }}
        >
          <View
            style={{
              flex: 0.8,
              justifyContent: "center"
            }}
          >
            <View
              style={{
                borderRadius: 50,
                backgroundColor: color.green,
                width: 45,
                height: 45,
                justifyContent: "center",
                alignItems: "center"
              }}
            >
              <Text
                style={{ fontSize: 25, fontWeight: "bold", color: "white" }}
              >
                {name.substring(0, 1)}
              </Text>
            </View>
          </View>
          <View
            style={{
              flex: 4,
              justifyContent: "center"
            }}
          >
            <Text numberOfLines={2} style={styles.text}>
              {name}
              <Text> - </Text>
              <Text>{company}</Text>
            </Text>
          </View>
          {children}
        </View>
      );
    }
  }
}

const styles = StyleSheet.create({
  text: {
    fontWeight: "bold",
    fontSize: 16,
    color: "black",
    justifyContent: "center"
  }
});
