import React, { Component } from "react";
import { connect } from "react-redux";
import {
  View,
  Text,
  ScrollView,
  TouchableOpacity,
  TouchableWithoutFeedback,
  Dimensions,
  ImageBackground
} from "react-native";
import { color } from "../../../../styles/color";
import { material_colors } from "../../../../assets/color/materialColor";
import Icon from "react-native-vector-icons/FontAwesome";
import Header from "../../../../components/header/header";
import constants from "../../../../constants/constants";
import LinearGradient from "react-native-linear-gradient";

const { height, width } = Dimensions.get("window");
class InvoiceDetail extends Component {
  constructor(props) {
    super(props);
    this.state = {};
  }

  render() {
    return (
      <View style={{ flex: 1, backgroundColor: "white" }}>
        <Header
          headerColor={color.green}
          leftIconColor="white"
          leftIconName="arrow-left"
          leftIconType="icon"
          pageTitleColor="white"
          pageTitle="Invoice Detail"
          onPressLeftIcon={() => this.props.navigation.goBack()}
        />
        <ScrollView>
          <ImageBackground
            source={require("../../../../assets/image/invoice_bg.png")}
            style={{
              borderRadius: 5,
              height: 150,
              margin: 20,
              overflow: "hidden"
            }}
          >
            <View
              style={{
                padding: 20,
                position: "absolute",
                left: 0,
                bottom: 0
              }}
            >
              <Text style={{ color: "white", fontSize: 16, marginBottom: 5 }}>
                ID INVOICE
              </Text>
              <Text
                style={{
                  color: "white",
                  fontSize: 20,
                  fontWeight: "600",
                  marginBottom: 5
                }}
              >
                Rp. 12.000.000
              </Text>
              <Text style={{ color: "white", fontSize: 16 }}>
                {constants.MULTILANGUAGE(this.props.settings.bahasa).ordered_at}
                10 May 2019 03:00 PM
              </Text>
            </View>
            <View
              style={{
                backgroundColor: material_colors.amber_700,
                borderRadius: 15,
                height: 25,
                justifyContent: "center",
                paddingHorizontal: 15,
                position: "absolute",
                right: 10,
                top: 10
              }}
            >
              <Text style={{ color: "white", textAlign: "center" }}>
                ON PROCESS
              </Text>
            </View>
          </ImageBackground>
          <View style={{ height: 10 }} />
          <Text
            style={{ color: "black", fontWeight: "500", paddingHorizontal: 20 }}
          >
            {constants.MULTILANGUAGE(this.props.settings.bahasa).certificate}
          </Text>
          <View style={{ height: 5 }} />
          <View
            style={{
              justifyContent: "center",
              borderColor: color.greyPlaceholder,
              borderWidth: 1,
              borderRadius: 5,
              padding: 15,
              paddingHorizontal: 20,
              marginHorizontal: 20
            }}
          >
            <Text
              style={{ color: "black", fontWeight: "500", marginBottom: 10 }}
            >
              Sertifikasi Pengelolaan SPBU
            </Text>
            <View
              style={{ flexDirection: "row", justifyContent: "space-between" }}
            >
              <View style={{ flexDirection: "row" }}>
                <Icon
                  name="tag"
                  color="black"
                  size={15}
                  style={{ alignSelf: "center" }}
                />
                <Text style={{ marginLeft: 5 }}>Rp.6.500.000</Text>
              </View>
              <View style={{ flexDirection: "row" }}>
                <Icon
                  name="user"
                  color="black"
                  size={15}
                  style={{ alignSelf: "center" }}
                />
                <Text style={{ marginLeft: 5 }}>
                  1{" "}
                  {constants.MULTILANGUAGE(this.props.settings.bahasa).assessee}
                </Text>
              </View>
            </View>
          </View>
          <View style={{ height: 10 }} />
          <Text
            style={{ color: "black", fontWeight: "500", marginHorizontal: 20 }}
          >
            {constants.MULTILANGUAGE(this.props.settings.bahasa).payment}
          </Text>
          <View
            style={{
              paddingVertical: 20,
              borderBottomWidth: 1,
              borderBottomColor: color.darkGrey,
              paddingHorizontal: 20
            }}
          >
            <Text style={{ fontWeight: "500" }}>BANK TRANSFER</Text>
            <View style={{ height: 20 }} />
            <Text style={{ fontWeight: "500", color: "black" }}>
              01234567890 - VA NAS
            </Text>
          </View>
          <View
            style={{
              flexDirection: "row",
              height: 50,
              paddingHorizontal: 20,
              alignItems: "center",
              borderBottomWidth: 1,
              borderBottomColor: color.darkGrey
            }}
          >
            <Text style={{ color: color.darkGrey, fontWeight: "500" }}>
              SUBTOTAL
            </Text>
            <Text
              style={{
                position: "absolute",
                right: 20,
                color: "black",
                fontWeight: "500"
              }}
            >
              Rp. 59.500.000
            </Text>
          </View>
          <View
            style={{
              flexDirection: "row",
              height: 50,
              paddingHorizontal: 20,
              alignItems: "center",
              borderBottomWidth: 1,
              borderBottomColor: color.darkGrey
            }}
          >
            <Text style={{ color: color.darkGrey, fontWeight: "500" }}>
              {constants.MULTILANGUAGE(this.props.settings.bahasa).discount}
            </Text>
            <Text
              style={{
                position: "absolute",
                right: 20,
                color: "black",
                fontWeight: "500"
              }}
            >
              Rp. 500.000
            </Text>
          </View>
          <View
            style={{
              flexDirection: "row",
              height: 50,
              paddingHorizontal: 20,
              alignItems: "center",
              borderBottomWidth: 1,
              borderBottomColor: color.darkGrey
            }}
          >
            <Text style={{ color: color.darkGrey, fontWeight: "500" }}>
              {constants.MULTILANGUAGE(this.props.settings.bahasa).unique_kode}
            </Text>
            <Text
              style={{
                position: "absolute",
                right: 20,
                color: "black",
                fontWeight: "500"
              }}
            >
              Rp. 012
            </Text>
          </View>
          <View
            style={{
              flexDirection: "row",
              height: 50,
              paddingHorizontal: 20,
              alignItems: "center",
              borderBottomWidth: 1,
              borderBottomColor: color.darkGrey
            }}
          >
            <Text style={{ color: color.darkGrey, fontWeight: "500" }}>
              TOTAL
            </Text>
            <Text
              style={{
                position: "absolute",
                right: 20,
                color: "black",
                fontWeight: "500"
              }}
            >
              Rp. 59.000.012
            </Text>
          </View>
          <TouchableOpacity>
            <View
              style={{
                height: 40,
                padding: 20,
                justifyContent: "center",
                borderRadius: 5,
                borderWidth: 1,
                borderColor: color.green,
                margin: 20
              }}
            >
              <Text
                style={{
                  color: color.green,
                  fontWeight: "500",
                  textAlign: "center"
                }}
              >
                {
                  constants.MULTILANGUAGE(this.props.settings.bahasa)
                    .download_invoice
                }
              </Text>
            </View>
          </TouchableOpacity>
        </ScrollView>
        <TouchableWithoutFeedback style={{ position: "absolute", bottom: 0 }}>
          <LinearGradient
            end={{ x: 1, y: 0 }}
            colors={[color.green, color.lightGreen]}
            style={{ height: 50, width: width, justifyContent: "center" }}
          >
            <Text
              style={{ color: "white", fontWeight: "500", textAlign: "center" }}
            >
              {
                constants.MULTILANGUAGE(this.props.settings.bahasa)
                  .confirm_payment
              }
            </Text>
          </LinearGradient>
        </TouchableWithoutFeedback>
      </View>
    );
  }
}

const mapStateToProps = state => ({
  settings: state.settings
});

const mapDispatchToProps = dispatch => ({});

export default connect(
  mapStateToProps,
  mapDispatchToProps
)(InvoiceDetail);
