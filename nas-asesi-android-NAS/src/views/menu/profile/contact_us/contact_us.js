import React, { Component } from "react";
import {
  View,
  Text,
  TextInput,
  Dimensions,
  TouchableOpacity,
  Linking
} from "react-native";
import { color } from "../../../../styles/color";
import { connect } from "react-redux";
import actions from "../../../../actions";
import Header from "../../../../components/header/header";
import constants from "../../../../constants/constants";
import Button from "../../../../components/button/button";
import FormInput from "../../../../components/formInput/formInput";

const { width, height } = Dimensions.get("window");

class ContactUs extends Component {
  constructor(props) {
    super(props);
    this.state = {
      message: "",
      phone_number: constants.CONTACT
    };
  }

  _gantiBahasa = bahasa => {
    this.props.gantiBahasa(bahasa, response => response);
  };

  onChangeText = (type, value) => {
    let state = this.state;
    state[type] = value;
    this.setState(state);
  };

  _sendMessage = message => {
    Linking.openURL(
      `http://api.whatsapp.com/send?text=${message}&phone=${this.state.phone_number}`
    );
    this.props.navigation.goBack();
  };

  render() {
    return (
      <View style={{ flex: 1, backgroundColor: color.greyWhite }}>
        <Header
          headerColor={color.green}
          pageTitle={
            constants.MULTILANGUAGE(this.props.settings.bahasa).contact_us
          }
          pageTitleColor={"white"}
          leftIconType={"icon"}
          leftIconName="arrow-left"
          leftIconColor="white"
          onPressLeftIcon={() => this.props.navigation.goBack()}
        />
        <View style={{ height: 20 }} />
        <View
          style={{
            paddingHorizontal: 20
          }}
        >
          <Text>
            Tinggalkan pertanyaan atau pesan anda pada kolom pesan dibawah ini,
            dan kami akan segera menghubungi Anda
          </Text>
          <View style={{ height: 20 }} />
          <FormInput
            ref="message"
            type={"message"}
            placeholder={"Pesan"}
            keyboardType={"default"}
            value={this.state.message}
            onChangeText={(type, value) => this.onChangeText("message", value)}
          />
        </View>
        <View
          style={{
            width: width,
            position: "absolute",
            bottom: 20,
            flex: 1,
            paddingHorizontal: 20
          }}
        >
          <Button
            title="Kirim Pesan"
            titleColor="white"
            onPressed={() => this._sendMessage(this.state.message)}
          />
        </View>
      </View>
    );
  }
}

const mapStateToProps = state => ({
  settings: state.settings
});

const mapDispatchToProps = dispatch => ({
  gantiBahasa: (data, callback) =>
    dispatch(actions.actionsAPI.settings.gantiBahasa(data, callback))
});

export default connect(mapStateToProps, mapDispatchToProps)(ContactUs);
