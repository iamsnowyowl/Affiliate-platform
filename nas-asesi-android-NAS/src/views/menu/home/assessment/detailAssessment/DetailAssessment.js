import React, { Component } from "react";
import {
  View,
  Text,
  TouchableOpacity,
  TouchableWithoutFeedback,
  ScrollView,
  Image,
  Dimensions,
  ToastAndroid,
  Modal,
  TextInput,
  StyleSheet
} from "react-native";
import { connect } from "react-redux";
import { color } from "../../../../../styles/color";
import actions from "../../../../../actions";
import style from "../../style";
import Header from "../../../../../components/header/header";
import constants from "../../../../../constants/constants";
import ExpandCollapse from "../../../../../components/expandCollapse/expandCollapse";
import ImagePicker from "react-native-image-picker";
import Moment from "moment";
import Dialog from "../../../../../components/dialog/dialog";
import LoadingBar from "../../../../../components/loadingBar/loadingbar";
import Icon from "react-native-vector-icons/FontAwesome";
import DocumentPicker from "react-native-document-picker";
import RNFS from "react-native-fs";
import Toast from "react-native-easy-toast";

const { height, width } = Dimensions.get("screen");

class DetailAssessment extends Component {
  constructor(props) {
    super(props);
    this.state = {
      check: "0",
      method: "",
      show: false,
      assessment: {},
      selectedImg: "",
      showImage: false,
      persyaratan_umum: [],
      status_assessment: [],
      master_portfolio_id: "",
      assessment_id: this.props.navigation.getParam("assessment_id")
    };
  }

  componentDidMount() {
    let id = this.props.navigation.getParam("assessment_id");
    let detail = this.props.assessments.find(function(element) {
      return element.assessment_id == id;
    });
    this.setState({ assessment: detail });
    this.getStatusAssessment();
    this.getPortofolio();
  }

  getPortofolio() {
    let data = {
      secret_key: this.props.auth.secret_key,
      username_email: this.props.upn
    };
    this.props.getPersyaratanUmum(data, response => {
      if (response.status == 200) {
        this.setState({ persyaratan_umum: response.data.data });
      }
    });
    this.props.getPortofolioDasar(
      this.props.navigation.getParam("assessment_id"),
      this.props.auth.secret_key,
      this.props.upn,
      null,
      response => response
    );
  }

  onChangeText = (type, value) => {
    let state = this.state;
    state[type] = value;
    this.setState(state);
  };

  submitText(master_portfolio_id, text_value) {
    let post = {
      master_portfolio_id: master_portfolio_id,
      form_value: text_value
    };
    this.props.postPortfolios(
      this.props.navigation.getParam("assessment_id"),
      this.props.auth.secret_key,
      this.props.upn,
      post,
      response => {
        this.setState({ show: false });
        if (response.status == 201) {
          this.refs.toast.show(
            constants.MULTILANGUAGE(this.props.settings.bahasa).success +
              " update portfolio"
          );
          this.getPortofolio();
        } else {
          this.refs.toast.show(
            constants.MULTILANGUAGE(this.props.settings.bahasa).failed +
              " update portofolio"
          );
        }
      }
    );
  }

  selectPhoto(master_portfolio_id, type) {
    const options = {
      quality: 1.0,
      maxWidth: 500,
      maxHeight: 500,
      storageOptions: {
        skipBackup: true
      }
    };

    ImagePicker.launchCamera(options, response => {
      this.dialog._closeDialog();
      if (response.error) {
        ToastAndroid.show(response.error, ToastAndroid.SHORT);
      } else if (response.didCancel) {
      } else {
        this.setState({ show: true });
        let post = {
          master_portfolio_id: master_portfolio_id,
          form_value: response.data,
          filename: response.fileName
        };
        switch (type) {
          case "post_pu":
            this.props.postPersyaratanUmum(
              this.props.auth.secret_key,
              this.props.upn,
              post,
              response => {
                this.setState({ show: false });
                if (response.status == 201) {
                  constants.MULTILANGUAGE(this.props.settings.bahasa).success +
                    " upload portfolio";
                  this.getPortofolio();
                } else {
                  this.refs.toast.show(
                    constants.MULTILANGUAGE(this.props.settings.bahasa).failed +
                      " upload portofolio"
                  );
                }
              }
            );
            break;
          case "post":
            this.props.postPortfolios(
              this.props.navigation.getParam("assessment_id"),
              this.props.auth.secret_key,
              this.props.upn,
              post,
              response => {
                this.setState({ show: false });
                if (response.status == 201) {
                  this.refs.toast.show(
                    constants.MULTILANGUAGE(this.props.settings.bahasa)
                      .success + " upload portfolio"
                  );
                  this.getPortofolio();
                } else {
                  this.refs.toast.show(
                    constants.MULTILANGUAGE(this.props.settings.bahasa).failed +
                      " upload portofolio"
                  );
                }
              }
            );
            break;
          default:
            break;
        }
      }
    });
  }

  pickFile = async (master_portfolio_id, type) => {
    this.dialog._closeDialog();
    this.setState({ show: true });
    try {
      const res = await DocumentPicker.pick({
        type: [DocumentPicker.types.allFiles]
      });
      res.size < 5500000
        ? RNFS.readFile(res.uri, "base64")
            .then(file => {
              let post = {
                master_portfolio_id: master_portfolio_id,
                form_value: file,
                filename: res.name
              };
              switch (type) {
                case "post_pu":
                  this.props.postPersyaratanUmum(
                    this.props.auth.secret_key,
                    this.props.upn,
                    post,
                    response => {
                      this.setState({ show: false });
                      if (response.status == 201) {
                        constants.MULTILANGUAGE(this.props.settings.bahasa)
                          .success + " upload portfolio";
                        this.getPortofolio();
                      } else {
                        this.refs.toast.show(
                          constants.MULTILANGUAGE(this.props.settings.bahasa)
                            .failed + " upload portofolio"
                        );
                      }
                    }
                  );
                  break;
                case "post":
                  this.props.postPortfolios(
                    this.props.navigation.getParam("assessment_id"),
                    this.props.auth.secret_key,
                    this.props.upn,
                    post,
                    response => {
                      this.setState({ show: false });
                      if (response.status == 201) {
                        this.refs.toast.show(
                          constants.MULTILANGUAGE(this.props.settings.bahasa)
                            .success + " upload portofolio"
                        );
                        this.getPortofolio();
                      } else {
                        this.refs.toast.show(
                          constants.MULTILANGUAGE(this.props.settings.bahasa)
                            .failed + " upload portofolio"
                        );
                      }
                    }
                  );
                  break;
                default:
                  break;
              }
            })
            .catch(err => {
              this.setState({ show: false });
            })
        : this.refs.toast.show(
            constants.MULTILANGUAGE(this.props.settings.bahasa).size_too_large
          );
    } catch (error) {
      if (DocumentPicker.isCancel(error)) {
        this.setState({ show: false });
        // User cancelled the picker, exit any dialogs or menus and move on
      } else {
        this.setState({ show: false });
        throw error;
      }
    }
  };

  chooseFileDialog(applicant_portfolio_id) {
    return (
      <Dialog
        ref={action => (this.dialog = action)}
        title={constants.MULTILANGUAGE(this.props.settings.bahasa).select_photo}
      >
        <TouchableOpacity
          onPress={() =>
            this.selectPhoto(
              this.state.master_portfolio_id,
              this.state.method,
              applicant_portfolio_id
            )
          }
          style={{ height: 40, width: 150 }}
        >
          <Text style={{ color: "black" }}>
            {constants.MULTILANGUAGE(this.props.settings.bahasa).take_a_picture}
          </Text>
        </TouchableOpacity>
        <TouchableOpacity
          onPress={() =>
            this.pickFile(
              this.state.master_portfolio_id,
              this.state.method,
              applicant_portfolio_id
            )
          }
          style={{ height: 40, width: 150 }}
        >
          <Text style={{ color: "black" }}>
            {
              constants.MULTILANGUAGE(this.props.settings.bahasa)
                .choose_from_library
            }
          </Text>
        </TouchableOpacity>
        <View style={{ height: 10 }} />
        <TouchableOpacity onPress={() => this.dialog._closeDialog()}>
          <Text style={{ textAlign: "right", paddingRight: 15 }}>
            {constants.MULTILANGUAGE(this.props.settings.bahasa).cancel}
          </Text>
        </TouchableOpacity>
      </Dialog>
    );
  }

  deletePersyaratan(persyaratan_id) {
    this.setState({ show: true });
    this.props.deletePersyaratan(
      this.props.auth.secret_key,
      this.props.upn,
      persyaratan_id,
      response => {
        this.setState({ show: false });
        response.status == 200
          ? this.getPortofolio()
          : this.refs.toast.show("Gagal delete file");
      }
    );
  }

  deletePortfolio(applicant_portfolio_id) {
    this.setState({ show: true });
    this.props.deletePortfolios(
      this.props.navigation.getParam("assessment_id"),
      applicant_portfolio_id,
      this.props.auth.secret_key,
      this.props.upn,
      response => {
        this.setState({ show: false });
        this.getPortofolio();
      }
    );
  }

  getStatusAssessment() {
    this.props.getStatusAssessment(
      this.props.navigation.getParam("assessment_id"),
      this.props.auth.secret_key,
      this.props.upn,
      response => {
        this.setState({ status_assessment: response.data.data });
      }
    );
  }

  render() {
    const ShowImage = () => {
      return (
        <Modal
          animationType={"fade"}
          transparent={true}
          visible={this.state.showImage}
          onRequestClose={() => this.setState({ showImage: false })}
        >
          <TouchableWithoutFeedback
            onPress={() => this.setState({ showImage: false })}
          >
            <View
              style={{
                flex: 1,
                backgroundColor: color.modalBackground,
                justifyContent: "center",
                alignItems: "center"
              }}
            >
              <TouchableWithoutFeedback onPress={() => null}>
                <View
                  style={{
                    backgroundColor: "white",
                    borderRadius: 12
                  }}
                >
                  <Image
                    style={{
                      width: 250,
                      height: 250,
                      borderRadius: 12,
                      overflow: "hidden",
                      alignSelf: "center"
                    }}
                    source={{ uri: constants.URL + this.state.selectedImg }}
                  />
                  <TouchableOpacity
                    onPress={() => this.setState({ showImage: false })}
                    style={{
                      position: "absolute",
                      top: -6,
                      right: -6,
                      elevation: 10
                    }}
                  >
                    <Icon name="times-circle" size={25} color={"red"} />
                  </TouchableOpacity>
                </View>
              </TouchableWithoutFeedback>
            </View>
          </TouchableWithoutFeedback>
        </Modal>
      );
    };
    const { assessment } = this.state;
    return (
      <View style={{ flex: 1, paddingBottom: 10, position: "relative" }}>
        <Header
          pageTitle={
            constants.MULTILANGUAGE(this.props.settings.bahasa)
              .detail_assessment
          }
          headerColor={color.green}
          leftIconType={"icon"}
          leftIconName={"arrow-left"}
          onPressLeftIcon={() => this.props.navigation.goBack()}
          pageTitleColor={"white"}
          leftIconColor={"white"}
        />
        <ScrollView>
          <View style={{ flex: 1, paddingHorizontal: 20, paddingTop: 15 }}>
            <View>
              <Text style={styles.title}>
                {
                  constants.MULTILANGUAGE(this.props.settings.bahasa)
                    .assessment_title
                }
              </Text>
              <Text style={styles.desc}>{assessment.title}</Text>
              <View style={styles.line(false)} />
              {/* --------------------------------------------------------------- */}
              <Text style={styles.title}>
                {
                  constants.MULTILANGUAGE(this.props.settings.bahasa)
                    .assessment_date
                }
              </Text>
              <Text style={styles.desc}>
                {Moment(assessment.start_date).format("DD MMMM YYYY")}
              </Text>
              <View style={styles.line(false)} />
              {/* --------------------------------------------------------------- */}
              {this.state.status_assessment.length > 0 ? (
                this.state.status_assessment.map((item, index) => {
                  return (
                    <View key={index}>
                      <Text style={styles.title}>
                        {
                          constants.MULTILANGUAGE(this.props.settings.bahasa)
                            .assessor_name
                        }
                      </Text>
                      <Text style={styles.desc}>{item.assessor_name}</Text>
                      <View style={styles.line(false)} />
                      {/* --------------------------------------------------------------- */}
                      <Text style={styles.title}>
                        {
                          constants.MULTILANGUAGE(this.props.settings.bahasa)
                            .schema_label
                        }
                      </Text>
                      <Text style={styles.desc}>
                        {item.schema_label != "" ? item.schema_label : "-"}
                      </Text>
                      <View style={styles.line(false)} />
                      {/* --------------------------------------------------------------- */}
                      <Text style={styles.title}>
                        {
                          constants.MULTILANGUAGE(this.props.settings.bahasa)
                            .test_method
                        }
                      </Text>
                      <Text style={styles.desc}>
                        {item.test_method == "competency"
                          ? constants.MULTILANGUAGE(this.props.settings.bahasa)
                              .test_competency
                          : item.test_method == "portfolio"
                          ? constants.MULTILANGUAGE(this.props.settings.bahasa)
                              .test_portfolio
                          : "-"}
                      </Text>
                      <View style={styles.line(false)} />
                      {/* --------------------------------------------------------------- */}
                      <Text style={styles.title}>
                        {
                          constants.MULTILANGUAGE(this.props.settings.bahasa)
                            .status_recommendation
                        }
                      </Text>
                      <Text style={styles.desc}>
                        {item.status_recomendation == "K"
                          ? constants.MULTILANGUAGE(this.props.settings.bahasa)
                              .recommended
                          : item.status_recomendation == "BK"
                          ? constants.MULTILANGUAGE(this.props.settings.bahasa)
                              .not_recomended
                          : "-"}
                      </Text>
                      <View style={styles.line(false)} />
                      {/* --------------------------------------------------------------- */}
                      <Text style={styles.title}>
                        {
                          constants.MULTILANGUAGE(this.props.settings.bahasa)
                            .desc_for_recommendation
                        }
                      </Text>
                      <Text style={styles.desc}>
                        {item.description_for_recomendation}
                      </Text>
                      <View style={styles.line(false)} />
                      {/* --------------------------------------------------------------- */}
                      <Text style={styles.title}>
                        {
                          constants.MULTILANGUAGE(this.props.settings.bahasa)
                            .status_pleno
                        }
                      </Text>
                      <Text style={styles.desc}>
                        {item.status_graduation == "L"
                          ? constants.MULTILANGUAGE(this.props.settings.bahasa)
                              .competent
                          : item.status_graduation == "TL"
                          ? constants.MULTILANGUAGE(this.props.settings.bahasa)
                              .not_competent
                          : "-"}
                      </Text>
                      <View style={styles.line(false)} />
                    </View>
                  );
                })
              ) : (
                <View />
              )}
              {/* --------------------------------------------------------------- */}
              <Text style={styles.title}>
                {
                  constants.MULTILANGUAGE(this.props.settings.bahasa)
                    .assessment_address
                }
              </Text>
              <Text style={styles.desc}>{assessment.address}</Text>
              <View style={styles.line(false)} />
              {/* --------------------------------------------------------------- */}
              <Text style={styles.title}>
                {
                  constants.MULTILANGUAGE(this.props.settings.bahasa)
                    .assessment_note
                }
              </Text>
              <Text style={styles.desc}>{assessment.notes}</Text>
              <View style={styles.line(false)} />
              {/* --------------------------------------------------------------- */}
              <Text style={styles.title}>
                {constants.MULTILANGUAGE(this.props.settings.bahasa).tuk_name}
              </Text>
              <Text style={styles.desc}>{assessment.tuk_name}</Text>
              <View style={styles.line(true)} />
            </View>
            {/* detail asesmen */}
            <ExpandCollapse
              title={
                constants.MULTILANGUAGE(this.props.settings.bahasa)
                  .general_requirements
              }
            >
              {this.state.persyaratan_umum.length > 0
                ? this.state.persyaratan_umum.map((item, index) => {
                    return (
                      <View
                        key={index}
                        style={{
                          marginBottom: 10,
                          paddingBottom: 10
                        }}
                      >
                        <Text
                          style={{
                            color: color.black,
                            fontSize: 14,
                            fontWeight: "bold"
                          }}
                        >
                          {item.form_name}
                          <Text style={{ color: "red", fontWeight: "normal" }}>
                            *maks. 5mb
                          </Text>
                        </Text>
                        {item.master_portfolio_id !=
                        "b5a1d6c3-a625-46e7-9ca4-543e5a8022d6" ? (
                          <TouchableOpacity
                            onPress={() => {
                              this.dialog._openDialog();
                              this.setState({
                                method: "post_pu",
                                master_portfolio_id: item.master_portfolio_id
                              });
                            }}
                            style={style.btn}
                          >
                            <Text style={{ color: color.black }}>
                              {
                                constants.MULTILANGUAGE(
                                  this.props.settings.bahasa
                                ).upload_image
                              }
                            </Text>
                          </TouchableOpacity>
                        ) : null}
                        {item.persyaratan.length > 0 ? (
                          item.persyaratan.map((item2, index2) => {
                            return (
                              <View
                                key={index2}
                                style={{
                                  marginTop: 5,
                                  paddingBottom: 5,
                                  borderBottomWidth: 1,
                                  borderBottomColor: color.darkGrey
                                }}
                              >
                                {item.form_type == "file" &&
                                item2.form_value != "" ? (
                                  <View>
                                    <Text
                                      style={{
                                        fontSize: 12,
                                        color: "black",
                                        textAlign: "right"
                                      }}
                                    >
                                      {item2.filename}
                                    </Text>
                                    <View
                                      style={{
                                        flexDirection: "row",
                                        justifyContent: "space-between"
                                      }}
                                    >
                                      <TouchableOpacity
                                        onPress={() =>
                                          this.deletePersyaratan(
                                            item2.persyaratan_umum_id
                                          )
                                        }
                                        style={styles.btnDelete}
                                      >
                                        <Text style={{ color: "red" }}>
                                          Delete
                                        </Text>
                                      </TouchableOpacity>
                                      <View style={{ width: 5 }} />
                                      {item2.ext == "jpg" ||
                                      item2.ext == "jpeg" ||
                                      item2.ext == "png" ? (
                                        <TouchableOpacity
                                          onPress={() =>
                                            this.setState(
                                              {
                                                selectedImg: item2.form_value
                                              },
                                              () =>
                                                this.setState({
                                                  showImage: true
                                                })
                                            )
                                          }
                                          style={styles.btnShowImg}
                                        >
                                          <Text style={{ color: "white" }}>
                                            {
                                              constants.MULTILANGUAGE(
                                                this.props.settings.bahasa
                                              ).show_image
                                            }
                                          </Text>
                                        </TouchableOpacity>
                                      ) : item2.ext == "pdf" ? (
                                        <TouchableOpacity
                                          onPress={() =>
                                            this.props.navigation.navigate(
                                              "WebviewPage",
                                              {
                                                pageName: "Preview",
                                                url: `https://docs.google.com/gview?embedded=true&url=${constants.URL}${item2.form_value}`
                                              }
                                            )
                                          }
                                          style={styles.btnShowImg}
                                        >
                                          <Text style={{ color: "white" }}>
                                            Lihat
                                          </Text>
                                        </TouchableOpacity>
                                      ) : item2.ext == "docx" ? (
                                        <TouchableOpacity
                                          onPress={() =>
                                            this.props.navigation.navigate(
                                              "WebviewPage",
                                              {
                                                pageName: "Preview",
                                                url: `${constants.URL}${item2.form_value}`
                                              }
                                            )
                                          }
                                          style={styles.btnShowImg}
                                        >
                                          <Text style={{ color: "white" }}>
                                            Lihat
                                          </Text>
                                        </TouchableOpacity>
                                      ) : null}
                                    </View>
                                  </View>
                                ) : null}
                              </View>
                            );
                          })
                        ) : (
                          <View
                            style={{
                              marginTop: 5,
                              borderBottomWidth: 1,
                              borderBottomColor: color.darkGrey
                            }}
                          />
                        )}
                        {this.chooseFileDialog("")}
                      </View>
                    );
                  })
                : null}
            </ExpandCollapse>
            {/* portofolio dasar */}
            <ExpandCollapse
              title={
                constants.MULTILANGUAGE(this.props.settings.bahasa)
                  .basic_requirements
              }
            >
              {this.props.portfolioDasar.length > 0
                ? this.props.portfolioDasar.map((item, index) => {
                    return item.form_type != "file_online" ? (
                      <View
                        key={index}
                        style={{
                          marginBottom: 10,
                          paddingBottom: 10,
                          borderBottomWidth: 1,
                          borderBottomColor: color.darkGrey
                        }}
                      >
                        <Text
                          style={{
                            color: color.black,
                            fontSize: 14,
                            fontWeight: "bold"
                          }}
                        >
                          {item.form_name}
                          <Text style={{ color: "red", fontWeight: "normal" }}>
                            *maks. 5mb
                          </Text>
                        </Text>
                        {item.applicant_portfolio.length > 0
                          ? item.applicant_portfolio.map((item2, index2) => {
                              var submitText = "";
                              return (
                                <View key={index2}>
                                  {item.form_type == "file" &&
                                  item2.form_value == "" ? (
                                    <TouchableOpacity
                                      onPress={() => {
                                        this.dialog._openDialog();
                                        this.setState({
                                          method: "post",
                                          master_portfolio_id:
                                            item.master_portfolio_id
                                        });
                                      }}
                                      style={style.btn}
                                    >
                                      <Text style={{ color: color.black }}>
                                        {
                                          constants.MULTILANGUAGE(
                                            this.props.settings.bahasa
                                          ).upload_image
                                        }
                                      </Text>
                                    </TouchableOpacity>
                                  ) : item.form_type == "file" &&
                                    item2.form_value != "" ? (
                                    <View>
                                      {item.is_multiple == 1 ? (
                                        <Text
                                          style={{
                                            color: "black",
                                            fontSize: 12,
                                            textAlign: "right"
                                          }}
                                        >
                                          {item2.filename}
                                        </Text>
                                      ) : null}
                                      <View
                                        style={{
                                          flexDirection: "row",
                                          justifyContent: "space-between"
                                        }}
                                      >
                                        <TouchableOpacity
                                          onPress={() =>
                                            this.deletePortfolio(
                                              item2.applicant_portfolio_id
                                            )
                                          }
                                          style={styles.btnDelete}
                                        >
                                          <Text style={{ color: "red" }}>
                                            Delete
                                          </Text>
                                        </TouchableOpacity>
                                        <View style={{ width: 5 }} />
                                        {item2.ext == "jpg" ||
                                        item2.ext == "jpeg" ||
                                        item2.ext == "png" ? (
                                          <TouchableOpacity
                                            onPress={() =>
                                              this.setState(
                                                {
                                                  selectedImg: item2.form_value
                                                },
                                                () =>
                                                  this.setState({
                                                    showImage: true
                                                  })
                                              )
                                            }
                                            style={styles.btnShowImg}
                                          >
                                            <Text style={{ color: "white" }}>
                                              {
                                                constants.MULTILANGUAGE(
                                                  this.props.settings.bahasa
                                                ).show_image
                                              }
                                            </Text>
                                          </TouchableOpacity>
                                        ) : item2.ext == "pdf" ? (
                                          <TouchableOpacity
                                            onPress={() =>
                                              this.props.navigation.navigate(
                                                "WebviewPage",
                                                {
                                                  pageName: "Preview",
                                                  url: `https://docs.google.com/gview?embedded=true&url=${constants.URL}${item2.form_value}`
                                                }
                                              )
                                            }
                                            style={styles.btnShowImg}
                                          >
                                            <Text style={{ color: "white" }}>
                                              Lihat
                                            </Text>
                                          </TouchableOpacity>
                                        ) : item2.ext == "docx" ? (
                                          <TouchableOpacity
                                            onPress={() =>
                                              this.props.navigation.navigate(
                                                "WebviewPage",
                                                {
                                                  pageName: "Preview",
                                                  url: `${constants.URL}${item2.form_value}`
                                                }
                                              )
                                            }
                                            style={styles.btnShowImg}
                                          >
                                            <Text style={{ color: "white" }}>
                                              Lihat
                                            </Text>
                                          </TouchableOpacity>
                                        ) : null}
                                      </View>
                                    </View>
                                  ) : null}
                                  {this.chooseFileDialog(
                                    item2.applicant_portfolio_id
                                  )}
                                </View>
                              );
                            })
                          : null}
                        {item.applicant_portfolio[0].form_value != "" ? (
                          <TouchableOpacity
                            onPress={() => {
                              this.dialog._openDialog();
                              this.setState({
                                method: "post",
                                master_portfolio_id: item.master_portfolio_id
                              });
                            }}
                            style={style.btn}
                          >
                            <Text style={{ color: color.black }}>
                              {
                                constants.MULTILANGUAGE(
                                  this.props.settings.bahasa
                                ).add_new_file
                              }
                            </Text>
                          </TouchableOpacity>
                        ) : null}
                      </View>
                    ) : null;
                  })
                : null}
            </ExpandCollapse>
            <View style={styles.labelStatus}>
              <Text
                style={{ color: "white", fontSize: 13, fontWeight: "bold" }}
              >
                {assessment.last_activity_state ==
                "ON_REVIEW_APPLICANT_DOCUMENT"
                  ? constants.MULTILANGUAGE(this.props.settings.bahasa)
                      .pra_assessment
                  : assessment.last_activity_state == "ON_COMPLETED_REPORT"
                  ? constants.MULTILANGUAGE(this.props.settings.bahasa)
                      .pra_assessment_selesai
                  : assessment.last_activity_state == "REAL_ASSESSMENT"
                  ? constants.MULTILANGUAGE(this.props.settings.bahasa)
                      .assessment
                  : assessment.last_activity_state == "PLENO_DOCUMENT_COMPLETED"
                  ? constants.MULTILANGUAGE(this.props.settings.bahasa)
                      .pleno_assessment
                  : assessment.last_activity_state == "PLENO_REPORT_READY"
                  ? constants.MULTILANGUAGE(this.props.settings.bahasa)
                      .pleno_assessment_selesai
                  : (assessment.last_activity_state == "PRINT_CERTIFICATE" &&
                      assessment.status_graduation == "L") ||
                    (assessment.last_activity_state == "PRINT_CERTIFICATE" &&
                      assessment.status_recomendation == "K")
                  ? constants.MULTILANGUAGE(this.props.settings.bahasa)
                      .publish_certificate
                  : (assessment.last_activity_state == "PRINT_CERTIFICATE" &&
                      assessment.status_graduation == "TL") ||
                    (assessment.last_activity_state == "PRINT_CERTIFICATE" &&
                      assessment.status_recomendation == "BK") ||
                    assessment.last_activity_state == "COMPLETED"
                  ? constants.MULTILANGUAGE(this.props.settings.bahasa)
                      .assessment_done
                  : constants.MULTILANGUAGE(this.props.settings.bahasa).soon}
              </Text>
            </View>
          </View>
        </ScrollView>
        <LoadingBar visibility={this.state.show} />
        {this.state.showImage ? <ShowImage /> : null}
        <Toast ref="toast" />
      </View>
    );
  }
}

const styles = StyleSheet.create({
  title: {
    marginBottom: 5,
    fontWeight: "bold"
  },
  desc: {
    color: color.black
  },
  line: end => ({
    marginTop: 20,
    borderBottomWidth: 1,
    marginBottom: end == true ? 0 : 20,
    borderBottomColor: color.greyPlaceholder
  }),
  btnShowImg: {
    borderRadius: 5,
    margin: 5,
    flex: 1,
    marginTop: 10,
    justifyContent: "center",
    height: 30,
    alignItems: "center",
    backgroundColor: color.green
  },
  btnDelete: {
    flex: 1,
    borderRadius: 5,
    margin: 5,
    marginTop: 10,
    justifyContent: "center",
    height: 30,
    borderColor: "red",
    borderWidth: 2,
    alignItems: "center"
  },
  btnReupload: {
    borderRadius: 5,
    margin: 5,
    flex: 1,
    marginTop: 10,
    justifyContent: "center",
    height: 30,
    borderColor: color.green,
    borderWidth: 2,
    alignItems: "center"
  },
  labelStatus: {
    position: "absolute",
    right: 10,
    top: 10,
    borderRadius: 5,
    padding: 5,
    backgroundColor: color.green
  },
  formInput: {
    backgroundColor: "white",
    paddingHorizontal: 5,
    borderRadius: 5,
    borderWidth: 1,
    borderColor: color.darkGrey
  }
});

const mapStateToProps = state => ({
  auth: state.auth,
  user: state.user,
  settings: state.settings,
  assessments: state.assessments,
  portfolioDasar: state.portfolioDasar,
  portfolioUmum: state.portfolioUmum,
  upn: state.upn
});

const mapDispatchToProps = dispatch => ({
  getPersyaratanUmum: (data, callback) => {
    dispatch(actions.actionsAPI.discover.getPersyaratanUmum(data, callback));
  },
  postPersyaratanUmum: (secret_key, upn, data, callback) => {
    dispatch(
      actions.actionsAPI.discover.postPersyaratanUmum(
        secret_key,
        upn,
        data,
        callback
      )
    );
  },
  deletePersyaratan: (secret_key, upn, persyaratan_id, callback) => {
    dispatch(
      actions.actionsAPI.discover.deletePersyaratanUmum(
        secret_key,
        upn,
        persyaratan_id,
        callback
      )
    );
  },
  getPortofolioDasar: (assessment_id, secret_key, username, data, callback) =>
    dispatch(
      actions.actionsAPI.portfolio.getPortofolioDasar(
        assessment_id,
        secret_key,
        username,
        data,
        callback
      )
    ),

  postPortfolios: (assessment_id, secret_key, username, data, callback) =>
    dispatch(
      actions.actionsAPI.portfolio.postPortfolios(
        assessment_id,
        secret_key,
        username,
        data,
        callback
      )
    ),
  deletePortfolios: (
    assessment_id,
    applicant_portfolio_id,
    secret_key,
    username,
    callback
  ) =>
    dispatch(
      actions.actionsAPI.portfolio.deletePortfolios(
        assessment_id,
        applicant_portfolio_id,
        secret_key,
        username,
        callback
      )
    ),
  getStatusAssessment: (assessment_id, secret_key, username, callback) =>
    dispatch(
      actions.actionsAPI.assessments.getStatusAssessment(
        secret_key,
        username,
        assessment_id,
        callback
      )
    )
});

export default connect(mapStateToProps, mapDispatchToProps)(DetailAssessment);
