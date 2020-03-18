import React, { Component } from "react";
import { connect } from "react-redux";
import { View, ToastAndroid, Dimensions } from "react-native";
import { color } from "../../../../styles/color";
import actions from "../../../../actions";
import LoadingBar from "../../../../components/loadingBar/loadingbar";
import SignatureCapture from "react-native-signature-capture";
import Button from "../../../../components/button/button";
import Header from "../../../../components/header/header";
import constants from "../../../../constants/constants";

const { width } = Dimensions.get("screen");

class SignatureCanvas extends Component {
  constructor(props) {
    super(props);
    this.state = {
      visible: false,
      signature: ""
    };
  }

  clearCanvas() {
    this.refs["sign"].resetImage();
  }

  saveSignature() {
    this.refs["sign"].saveImage();
  }

  _onSaveEvent(result) {
    // result.encoded => base64
    this._putSignature(result.encoded);
  }

  _putSignature(sign) {
    this.setState({ visible: true });
    const data = {
      signature: sign
    };
    this.props.updateProfile(
      false,
      this.props.auth.secret_key,
      this.props.upn,
      data,
      response => {
        this.setState({ visible: false });
        response.status == 200
          ? this.props.navigation.goBack()
          : ToastAndroid.show(
              "Gagal menyimpan tanda tangan",
              ToastAndroid.LONG
            );
      }
    );
  }

  render() {
    return (
      <View style={{ flex: 1 }}>
        <Header
          headerColor={color.green}
          pageTitle={
            constants.MULTILANGUAGE(this.props.settings.bahasa).sign_canvas
          }
          pageTitleColor="white"
          leftIconType="icon"
          leftIconColor="white"
          leftIconName="arrow-left"
          onPressLeftIcon={() => this.props.navigation.goBack()}
        />
        <SignatureCapture
          style={{ flex: 1 }}
          ref="sign"
          onSaveEvent={this._onSaveEvent.bind(this)}
          saveImageFileInExtStorage={false}
          showNativeButtons={false}
          showTitleLabel={false}
          viewMode={"portrait"}
        />
        <View
          style={{
            bottom: 20,
            width: width,
            position: "absolute",
            paddingHorizontal: 20
          }}
        >
          <Button
            title={
              constants.MULTILANGUAGE(this.props.settings.bahasa).clear_sign
            }
            titleColor="white"
            onPressed={() => this.clearCanvas()}
          />
          <View style={{ height: 15 }} />
          <Button
            title={
              constants.MULTILANGUAGE(this.props.settings.bahasa).save_sign
            }
            titleColor="white"
            onPressed={() => this.saveSignature()}
          />
        </View>
        <LoadingBar visibility={this.state.visible} />
      </View>
    );
  }
}

const mapStateToProps = state => ({
  auth: state.auth,
  upn: state.upn,
  settings: state.settings
});

const mapDispatchToProps = dispatch => ({
  updateProfile: (picture, secret_key, username_email, data, callback) =>
    dispatch(
      actions.actionsAPI.user.updateProfile(
        picture,
        secret_key,
        username_email,
        data,
        callback
      )
    )
});

export default connect(
  mapStateToProps,
  mapDispatchToProps
)(SignatureCanvas);
