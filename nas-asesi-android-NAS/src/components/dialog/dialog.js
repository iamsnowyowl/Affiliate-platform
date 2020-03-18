import React, { Component } from "react";
import { View, Text } from "react-native";
import Modal from "react-native-modal";

type Props = {
  title: String,
  description: String,
  dialogCanClose: boolean
};
export default class Dialog extends Component<Props> {
  constructor(props) {
    super(props);
    this.state = { onShow: false };
  }

  _closeDialog() {
    this.setState({ onShow: false });
  }

  _openDialog() {
    this.setState({ onShow: true });
  }

  render() {
    const { title, children, description, dialogCanClose = true } = this.props;
    return (
      <Modal
        isVisible={this.state.onShow}
        onBackdropPress={
          dialogCanClose == true ? this._closeDialog.bind(this) : null
        }
        onBackButtonPress={
          dialogCanClose == true ? this._closeDialog.bind(this) : null
        }
        animationIn="zoomInDown"
        animationOut="zoomOutDown"
        animationInTiming={600}
        animationOutTiming={600}
        backdropTransitionInTiming={600}
        backdropTransitionOutTiming={600}
        style={{ justifyContent: "center" }}
      >
        <View
          style={{
            backgroundColor: "white",
            borderRadius: 10,
            paddingTop: 10
          }}
        >
          <View>
            <Text
              style={{
                alignSelf: "center",
                color: "black",
                fontSize: 18,
                fontWeight: "bold",
                marginBottom: 15
              }}
            >
              {title}
            </Text>
            {description != null ? (
              <Text
                style={{
                  textAlign: "center",
                  color: "black",
                  fontSize: 18,
                  paddingHorizontal: 10
                }}
              >
                {description}
              </Text>
            ) : null}
          </View>
          <View
            style={{
              padding: 15
            }}
          >
            {children}
          </View>
        </View>
      </Modal>
    );
  }
}
