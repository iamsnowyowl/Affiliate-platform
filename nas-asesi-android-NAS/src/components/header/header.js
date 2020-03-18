import React, { Component } from "react";
import { View, Text, Image, TouchableOpacity } from "react-native";
import Icon from "react-native-vector-icons/FontAwesome";
import headerStyle from "../header/style";
import { color } from "../../styles/color";

type Props = {
  headerColor: any,
  onPressLeftIcon: any,
  leftIconType?: "icon",
  leftIconName?: String,
  leftIconColor?: String,
  title?: String,
  pageTitle?: String,
  pageTitleColor?: any,
  rightIconName: any,
  onPressRightIcon: any,
  rightIconColor: any
};
export default class Header extends Component<Props> {
  constructor(props) {
    super(props);
    this.state = {};
  }
  render() {
    const {
      headerColor,
      leftIconType,
      leftIconName,
      leftIconColor,
      title,
      pageTitle,
      pageTitleColor,
      rightIconName,
      onPressLeftIcon,
      onPressRightIcon,
      rightIconColor
    } = this.props;
    return (
      <View style={headerStyle.container(headerColor)}>
        <View
          style={{
            flex: 1,
            flexDirection: "row",
            alignSelf: "center"
          }}
        >
          {leftIconType === "icon" ? (
            <TouchableOpacity onPress={onPressLeftIcon} style={{ padding: 5 }}>
              <Icon name={leftIconName} size={20} color={leftIconColor} />
            </TouchableOpacity>
          ) : (
            <Image
              source={require("../../assets/image/nas_logo.png")}
              style={{
                width: 30,
                height: 30,
                justifyContent: "center",
                alignSelf: "center"
              }}
            />
          )}
          {title ? (
            <Text
              style={{
                fontWeight: "bold",
                alignSelf: "center",
                marginLeft: 10,
                color: color.black
              }}
            >
              {title}
            </Text>
          ) : null}
        </View>
        <View
          style={{
            flex: 1,
            flexDirection: "row",
            alignSelf: "center",
            justifyContent: "center"
          }}
        >
          <Text
            style={{
              textAlign: "center",
              fontWeight: "bold",
              color: pageTitleColor
            }}
          >
            {pageTitle}
          </Text>
        </View>
        <View
          style={{
            flex: 1,
            flexDirection: "row",
            justifyContent: "flex-end",
            alignSelf: "center"
          }}
        >
          {rightIconName ? (
            <TouchableOpacity
              style={{ padding: 10 }}
              onPress={onPressRightIcon}
            >
              <Icon
                name={rightIconName}
                size={20}
                color={rightIconColor ? rightIconColor : "white"}
              />
            </TouchableOpacity>
          ) : null}
        </View>
      </View>
    );
  }
}
