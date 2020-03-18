import React, { Component } from "react";
import { View, Text, Image, Dimensions } from "react-native";
import { color } from "../../styles/color";
import Moment from "moment";
const { width, height } = Dimensions.get("window");

type Props = {
  newsImg: any,
  newsTitle: any,
  newsTime: any,
  isHorizontal: false
};
export default class Newsitem extends Component<Props> {
  constructor(props) {
    super(props);
    this.state = {};
  }

  render() {
    const { newsImg, newsTitle, newsTime, isHorizontal } = this.props;
    return (
      <View
        style={{
          backgroundColor: color.white,
          width: isHorizontal == true ? 290 : width - 40,
          height: 150,
          elevation: 5,
          marginBottom: 8,
          marginRight: 15,
          marginHorizontal: isHorizontal == true ? 0 : 10,
          borderRadius: 5,
          flexDirection: "row"
        }}
      >
        <Image
          borderTopLeftRadius={5}
          borderBottomLeftRadius={5}
          source={{ uri: newsImg }}
          style={{ overflow: "hidden", height: 150, width: 150 }}
        />
        <View
          style={{
            padding: 10,
            width: isHorizontal == true ? 130 : width - 200,
            height: 150
          }}
        >
          <Text style={{ color: "black", fontWeight: "bold" }}>
            {newsTitle}
          </Text>
          <Text
            style={{
              color: "black",
              position: "absolute",
              left: 10,
              bottom: 10
            }}
          >
            {Moment(newsTime).format("DD/MM/YYYY")}
          </Text>
        </View>
      </View>
    );
  }
}
