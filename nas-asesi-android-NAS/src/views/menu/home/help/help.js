import React, { Component } from "react";
import { View, Text, TouchableOpacity, ScrollView } from "react-native";
import { color } from "../../../../styles/color";
import { connect } from "react-redux";
import Icon from "react-native-vector-icons/FontAwesome";
import Header from "../../../../components/header/header";
import constants from "../../../../constants/constants";
import ExpandCollapse from "../../../../components/expandCollapse/expandCollapse";

class Help extends Component {
  constructor(props) {
    super(props);
    this.state = {};
  }

  render() {
    return (
      <View style={{ flex: 1, backgroundColor: color.greyWhite }}>
        <Header
          headerColor={color.green}
          leftIconName="arrow-left"
          leftIconType="icon"
          leftIconColor="white"
          onPressLeftIcon={() => this.props.navigation.goBack()}
          pageTitle={constants.MULTILANGUAGE(this.props.settings.bahasa).help}
          pageTitleColor="white"
        />
        <ScrollView>
          <View style={{ padding: 20 }}>
            <Text
              style={{
                fontSize: 18,
                color: "black",
                fontWeight: "bold",
                textAlign: "center"
              }}
            >
              {constants.MULTILANGUAGE(this.props.settings.bahasa).help_title}
            </Text>
            <View style={{ height: 25 }} />
            <View
              style={{
                backgroundColor: "white",
                borderRadius: 8,
                elevation: 5,
                paddingHorizontal: 10
              }}
            >
              <ExpandCollapse
                title={
                  constants.MULTILANGUAGE(this.props.settings.bahasa)
                    .what_is_sertimedia
                }
                childrenBackground="white"
              >
                <Text style={{ textAlign: "justify" }}>
                  NAS adalah sebuah aplikasi yang mempermudah LSP (Lembaga
                  Setifikasi Profesi), TUK (Tempat Uji Kompetensi) dan Peserta
                  untuk saling terhubung dan terintegrasi.
                  {"\n"}
                  {"\n"}
                  Dengan sistem yang terintegrasi membuat pengolahan data,
                  pengaturan jadwal dan proses administrasi sertifikasi profesi
                  menjadi lebih mudah. Juga membuat dokumen yang sebelumnya
                  menumpuk menjadi lebih ringkas dan mudah dicari
                </Text>
              </ExpandCollapse>
            </View>
            <View style={{ height: 15 }} />
            <View
              style={{
                backgroundColor: "white",
                borderRadius: 8,
                elevation: 5,
                paddingHorizontal: 10
              }}
            >
              <ExpandCollapse
                title={
                  constants.MULTILANGUAGE(this.props.settings.bahasa)
                    .what_is_certification
                }
                childrenBackground="white"
              >
                <Text style={{ textAlign: "justify" }}>
                  Istilah sertifikasi berasal dari bahasa Inggris
                  ’certification’ dengan yang berarti keterangan, pengesahan,
                  ijazah, sertifikat, brevet, diploma, keterangan.
                  {"\n"}
                  {"\n"}International Institute for Environment Develpoment
                  (IIED), pengertian sertifikasi adalah Prosedur dimana pihak
                  ketiga memberikan jaminan tertulis bahwa suatu produk, proses
                  atas jasa telah memenuhi standar tertentu, berdasarkan audit
                  yang dilaksanakan dengan prosedur yang disepakati. Sertifikasi
                  berkaitan dengan pelabelan produk untuk proses komunikasi
                  pasar.
                </Text>
              </ExpandCollapse>
            </View>
            <View style={{ height: 15 }} />
            <View
              style={{
                backgroundColor: "white",
                borderRadius: 8,
                elevation: 5,
                paddingHorizontal: 10
              }}
            >
              <ExpandCollapse
                title={
                  constants.MULTILANGUAGE(this.props.settings.bahasa)
                    .what_sertimedia_can_do
                }
                childrenBackground="white"
              >
                <Text style={{ textAlign: "justify" }}>
                  – Melakukan monitoring dari progres sertifikasi dalam satu
                  dashboard
                  {"\n"}
                  {"\n"}– Memudahkan analisa, pengaturan, dan verifikasi data
                  Applicant dan Assesors di setiap proses
                  {"\n"}
                  {"\n"}– Assesors, Applicant, TUK dan LSP dapat berinteraksi
                  langsung dalam satu aplikasi
                  {"\n"}
                  {"\n"}– Aplikasi dapat terintegrasi dengan TUK dan BNSP secara
                  realtime
                  {"\n"}
                  {"\n"}– Proses pembayaran mudah
                  {"\n"}
                  {"\n"}– Memantau jadwal secara realtime dan terintegrasi
                  dengan LSP terkait
                </Text>
              </ExpandCollapse>
            </View>
            <View style={{ height: 15 }} />
            <View
              style={{
                backgroundColor: "white",
                borderRadius: 8,
                elevation: 5,
                paddingHorizontal: 10
              }}
            >
              <ExpandCollapse
                title={
                  constants.MULTILANGUAGE(this.props.settings.bahasa)
                    .why_use_Sertimedia
                }
                childrenBackground="white"
              >
                <Text>
                  Dengan menggunakan Aplikasi NAS, maka membuat proses
                  Sertifikasi Profesi #JadiLebihMudah dan terintegrasi
                </Text>
              </ExpandCollapse>
            </View>
            <View style={{ height: 15 }} />
            <View
              style={{
                backgroundColor: "white",
                borderRadius: 8,
                elevation: 5,
                paddingHorizontal: 10
              }}
            >
              <ExpandCollapse
                title={
                  constants.MULTILANGUAGE(this.props.settings.bahasa)
                    .how_to_use_sertimedia
                }
                childrenBackground="white"
              >
                <Text style={{ textAlign: "justify" }}>
                  - Sertifikasi : Menu sertifikasi akan berisi update dari
                  asesmen / skema yang sudah selesai anda ikuti
                  {"\n"}
                  {"\n"}- Jadwal : Untuk jadwal asesmen akan keluar apabila dari
                  TUK / LSP sudah memvalidasi data anda dan mendaftarkan anda
                  sebagai asesi di jadwal tersebut
                  {"\n"}
                  {"\n"}- Persyaratan dan Upload Dokumen : Setelah jadwal
                  asesmen anda sudah keluar, anda bisa mengupload persyaratan
                  pendukung untuk menjalani asesmen melalui fitur upload yang
                  tersedia
                </Text>
              </ExpandCollapse>
            </View>
          </View>
        </ScrollView>
      </View>
    );
  }
}

const mapStateToProps = state => ({
  auth: state.auth,
  settings: state.settings
});

const mapDispatchToProps = dispatch => ({});

export default connect(
  mapStateToProps,
  mapDispatchToProps
)(Help);
