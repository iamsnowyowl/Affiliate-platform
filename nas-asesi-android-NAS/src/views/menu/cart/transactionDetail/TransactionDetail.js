import React, { Component } from "react";
import { connect } from "react-redux";
import { View, Text, ScrollView, Image } from "react-native";
import { color } from "../../../../styles/color";
import { material_colors } from "../../../../assets/color/materialColor";
import Header from "../../../../components/header/header";
import constants from "../../../../constants/constants";
import { imgURL } from "../../../../assets/image/source";

class TransactionDetails extends Component {
  constructor(props) {
    super(props);
    this.state = {};
  }

  render() {
    return (
      <View style={{ flex: 1, backgroundColor: material_colors.grey_200 }}>
        <Header
          headerColor={color.green}
          pageTitle={
            constants.MULTILANGUAGE(this.props.settings.bahasa)
              .transaction_detail
          }
          pageTitleColor="white"
          leftIconType="icon"
          leftIconColor="white"
          leftIconName="arrow-left"
          onPressLeftIcon={() => this.props.navigation.goBack()}
        />
        <ScrollView>
          <View style={{ paddingHorizontal: 20, backgroundColor: "white" }}>
            <View
              style={{
                borderBottomColor: color.darkGrey,
                borderBottomWidth: 1,
                paddingVertical: 10
              }}
            >
              <Text style={{ color: "black" }}>Status</Text>
              <View style={{ height: 5 }} />
              <Text style={{ color: color.green }}>Transaction Completed</Text>
            </View>
            <View
              style={{
                flexDirection: "row",
                justifyContent: "space-between",
                borderBottomColor: color.darkGrey,
                borderBottomWidth: 1,
                paddingVertical: 10
              }}
            >
              <Text>
                {
                  constants.MULTILANGUAGE(this.props.settings.bahasa)
                    .transaction_date
                }
              </Text>
              <View style={{ height: 5 }} />
              <Text style={{ color: "black" }}>09 May 2019 09:00</Text>
            </View>
            <Text style={{ paddingVertical: 20, color: "black" }}>
              INV/20190428/UV/9823401
            </Text>
          </View>
          <View style={{ height: 10 }} />
          <View style={{ padding: 20, backgroundColor: "white" }}>
            <Text
              style={{ color: "black", fontWeight: "bold", marginBottom: 10 }}
            >
              {
                constants.MULTILANGUAGE(this.props.settings.bahasa)
                  .detail_certification
              }
            </Text>
            <Image
              source={{ uri: imgURL.imageSource }}
              style={{ height: 100, width: 150, resizeMode: "contain" }}
            />
            <View
              style={{
                justifyContent: "space-between",
                flexDirection: "row",
                marginVertical: 10
              }}
            >
              <Text style={{ flex: 1 }}>
                {
                  constants.MULTILANGUAGE(this.props.settings.bahasa)
                    .certification_name
                }
              </Text>
              <Text style={{ flex: 1, textAlign: "right" }}>
                Pengelolaan SPBU
              </Text>
            </View>
            <View
              style={{
                justifyContent: "space-between",
                flexDirection: "row",
                marginVertical: 10
              }}
            >
              <Text style={{ flex: 1 }}>
                {
                  constants.MULTILANGUAGE(this.props.settings.bahasa)
                    .certification_date
                }
              </Text>
              <Text style={{ flex: 1, textAlign: "right" }}>
                01 Juli 2019 - 04 Juli 2019
              </Text>
            </View>
            <View
              style={{
                justifyContent: "space-between",
                flexDirection: "row",
                marginVertical: 10
              }}
            >
              <Text style={{ flex: 1 }}>
                {constants.MULTILANGUAGE(this.props.settings.bahasa).organizer}
              </Text>
              <Text style={{ flex: 1, textAlign: "right" }}>LSP Energi</Text>
            </View>
            <View
              style={{
                justifyContent: "space-between",
                flexDirection: "row",
                marginVertical: 10
              }}
            >
              <Text style={{ flex: 1 }}>
                {constants.MULTILANGUAGE(this.props.settings.bahasa).location}
              </Text>
              <Text style={{ flex: 1, textAlign: "right" }}>
                Jl. Jati Murni No. 30, Jati Padang, Pasar Minggu, Jakarta
                Selatan
              </Text>
            </View>
          </View>
          <View style={{ height: 10 }} />
          <View style={{ padding: 20, backgroundColor: "white" }}>
            <Text style={{ color: "black", fontWeight: "bold" }}>
              {
                constants.MULTILANGUAGE(this.props.settings.bahasa)
                  .payment_information
              }
            </Text>
            <View
              style={{
                borderBottomColor: color.darkGrey,
                borderBottomWidth: 1,
                paddingVertical: 10,
                flexDirection: "row",
                justifyContent: "space-between"
              }}
            >
              <Text>
                {
                  constants.MULTILANGUAGE(this.props.settings.bahasa)
                    .payment_method
                }
              </Text>
              <Text style={{ color: "black" }}>VA NAS</Text>
            </View>
            <View
              style={{
                paddingVertical: 10,
                flexDirection: "row",
                justifyContent: "space-between"
              }}
            >
              <Text style={{ color: "black" }}>SUBTOTAL</Text>
              <Text style={{ color: "black", fontWeight: "bold" }}>
                Rp. 5.500.000
              </Text>
            </View>
            <View
              style={{
                paddingVertical: 10,
                flexDirection: "row",
                justifyContent: "space-between"
              }}
            >
              <Text style={{ color: "black" }}>
                {constants.MULTILANGUAGE(this.props.settings.bahasa).discount}
              </Text>
              <Text style={{ color: "black", fontWeight: "bold" }}>
                -Rp. 500.000
              </Text>
            </View>
            <View
              style={{
                paddingVertical: 10,
                flexDirection: "row",
                justifyContent: "space-between",
                borderBottomColor: color.darkGrey,
                borderBottomWidth: 1
              }}
            >
              <Text style={{ color: "black" }}>
                {
                  constants.MULTILANGUAGE(this.props.settings.bahasa)
                    .unique_kode
                }
              </Text>
              <Text style={{ color: "black", fontWeight: "bold" }}>
                Rp. 012
              </Text>
            </View>
            <View
              style={{
                paddingVertical: 10,
                flexDirection: "row",
                justifyContent: "space-between"
              }}
            >
              <Text style={{ color: "black" }}>TOTAL</Text>
              <Text style={{ color: color.green, fontWeight: "bold" }}>
                Rp. 5.000.012
              </Text>
            </View>
          </View>
        </ScrollView>
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
)(TransactionDetails);
