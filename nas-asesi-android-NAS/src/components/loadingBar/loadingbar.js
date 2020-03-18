import React, { Component } from "react";
import { View, Text, Modal, ActivityIndicator } from "react-native";
import { color } from "../../styles/color";
import constants from "../../constants/constants";

type Props = {
  visibility: boolean
};

export default class LoadingBar extends Component<Props> {
  constructor(props) {
    super(props);
    this.state = { bahasa: "id" };
  }
  render() {
    const { visibility } = this.props;
    return (
      <Modal
        animationType={"fade"}
        transparent={true}
        visible={visibility}
        onRequestClose={() => console.log("closed")}
      >
        <View
          style={{
            flex: 1,
            backgroundColor: color.modalBackground,
            justifyContent: "center",
            alignItems: "center"
          }}
        >
          <View
            style={{
              backgroundColor: "white",
              width: 150,
              borderRadius: 12,
              height: 150,
              padding: 10,
              alignItems: "center",
              justifyContent: "center",
              alignSelf: "center"
            }}
          >
            <ActivityIndicator
              color={color.green}
              animating={true}
              size={"large"}
            />
            <Text
              style={{
                textAlign: "center",
                marginTop: 20,
                color: color.black,
                fontSize: 16,
                fontWeight: "bold"
              }}
            >
              {constants.MULTILANGUAGE(this.state.bahasa).loading}
            </Text>
          </View>
        </View>
      </Modal>
    );
  }
}
