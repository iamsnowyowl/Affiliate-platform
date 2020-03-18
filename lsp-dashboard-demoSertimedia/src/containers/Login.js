import React, { Component } from "react";
import { PostData } from "../services/PostData";
import { Redirect } from "react-router-dom";
import {
  Button,
  Card,
  CardBody,
  Col,
  Container,
  Alert,
  Form,
  Row,
  Modal,
  ModalHeader,
  ModalBody,
  ModalFooter
} from "reactstrap";
import { Input } from "antd";
import { withAlert } from "react-alert";
import axios from "axios";
import LoadingOverlay from "react-loading-overlay";
import {
  NotificationContainer,
  NotificationManager
} from "react-notifications";
import firebase from "firebase/app";
import "firebase/messaging";

import "../css/Login.css";
import "../css/Button.css";
import {
  baseUrl,
  path_forgotPass,
  path_refreshToken,
  Brand_LSP
} from "../components/config/config";
import { Digest } from "./Helpers/digest";

import "antd/dist/antd.css";

class Login extends Component {
  constructor(props) {
    super(props);
    this.state = {
      loading: false,
      loadingLogo: true,
      username_email: "",
      password: "",
      logged_in: false,
      message: "",
      hidden: true,
      modal: false,
      nestedModal: false,
      closeAll: false,
      response: "",
      register_id: ""
    };
  }

  toggle = () => {
    this.setState({
      modal: !this.state.modal
    });
  };

  // componentDidMount() {
  //   return Axios.interceptors.response.use(
  //     response => {
  //       console.log("halo");
  //       return response;
  //     },
  //     error => {
  //       return error.status === 419
  //         ? console.log("error interceptor", error.response)
  //         : Promise.reject({ ...error });
  //     }
  //   );
  // }

  toggleNested = event => {
    event.preventDefault();
    this.setState({
      nestedModal: !this.state.nestedModal,
      closeAll: false
    });

    var data = new FormData();
    data.append("email", this.state.email);
    const options = {
      method: "POST",
      url: baseUrl + path_forgotPass,
      data: data
    };
    axios(options).then(response => {
      console.log(response);
      return response;
    });
  };

  toggleAll = () => {
    this.setState({
      nestedModal: !this.state.nestedModal,
      closeAll: true
    });
  };

  handleChange = event => {
    this.setState({ [event.target.name]: event.target.value });
  };

  setLocalStorage = responseJSON => {
    this.setState({ logged_in: true });
    localStorage.setItem("userdata", JSON.stringify(responseJSON.data));
    localStorage.setItem("secret_key", responseJSON.secret_key);
    localStorage.setItem("logged_in", responseJSON.data.logged_in);
    localStorage.setItem("identity_type", responseJSON.identity_type);
    localStorage.setItem("role", responseJSON.data.role_code);
    localStorage.setItem("bahasa", "id");
    let arr = [];
    const json = responseJSON.data;
    for (let index = 0; index < json.permission.length; index++) {
      const element = json.permission[index].sub_module_code;
      arr.push(element);
      localStorage.setItem("permission", arr);
    }
  };

  login = event => {
    event.preventDefault();
    const { history } = this.props;
    this.setState({ loading: true });

    if (this.state.username_email === "" || this.state.password === "") {
      this.setState({
        loading: false,
        hidden: false,
        message: "Username and password cannot empty"
      });
    }
    if (this.state.username_email && this.state.password) {
      PostData("/login", {
        username_email: this.state.username_email,
        password: this.state.password
      }).then(result => {
        this.setState({ loading: false });
        let responseJSON = result;
        if (responseJSON.responseStatus === "SUCCESS") {
          firebase
            .messaging()
            .requestPermission()
            .then(function() {
              return firebase.messaging().getToken();
            })
            .then(token => {
              //process sending token when loggin in
              const authentication = Digest(path_refreshToken + token, "PUT");
              const options = {
                method: authentication.method,
                headers: {
                  Authorization: authentication.digest,
                  "X-Lsp-Date": authentication.date,
                  "Content-Type": "application/json"
                },
                url: baseUrl + path_refreshToken + token,
                data: null
              };
              axios(options);
            });
          if (
            responseJSON.data.role_code === "DEV" ||
            responseJSON.data.role_code === "ADM" ||
            responseJSON.data.role_code === "ACS" ||
            responseJSON.data.role_code === "SUP" ||
            responseJSON.data.role_code === "APL"
          ) {
            this.setLocalStorage(responseJSON);
            history.push("/dashboard");
          } else {
            localStorage.clear();
            this.setState({
              hidden: false,
              message: "You aren't access,Please check again"
            });
          }
          return;
        }
        this.setState({
          response: responseJSON.error.code
        });
        switch (this.state.response) {
          case 400:
            localStorage.clear();
            this.setState({
              hidden: false,
              message: "Minimum password length is 6"
            });
            break;

          case 422:
            localStorage.clear();
            this.setState({
              hidden: false,
              message: responseJSON.error.message
            });
            break;

          case 419:
            NotificationManager.warning(
              "Masa trial anda telah habis,Harap menghubungi Admin NAS untuk info lebih lanjut",
              "Peringatan",
              5000
            );
            localStorage.clear();
            break;

          default:
            break;
        }
      });
      //process FCM
    }
  };

  onChange = e => {
    this.setState({ [e.target.name]: e.target.value });
  };

  render() {
    if (localStorage.getItem("logged_in") || this.state.logged_in) {
      return <Redirect to={"/"} />;
    }
    const { Logo } = Brand_LSP("demo");
    return (
      <LoadingOverlay active={this.state.loading} spinner text="Please Wait...">
        <div className="app flex-row align-items-center animated fadeIn backgroundColor">
          <Modal
            isOpen={this.state.modal}
            toggle={this.toggle}
            className={this.props.className}
          >
            <ModalHeader toggle={this.toggle}>
              <b>Forgot Password</b>
            </ModalHeader>
            <ModalBody>
              <Row className="center-block" style={{ width: "75%" }}>
                <Col md="20">
                  <Input
                    className="inputBox"
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Please input your email account"
                    onChange={this.handleChange}
                  />
                </Col>
                <br />
              </Row>
              <Row className="center-block">
                <Col>
                  <Button className="btn-submit" onClick={this.toggleNested}>
                    Submit
                  </Button>
                </Col>
              </Row>
              <Modal
                isOpen={this.state.nestedModal}
                toggle={this.toggleNested}
                onClosed={this.state.closeAll ? this.toggle : undefined}
              >
                <ModalHeader>Confirmation Message</ModalHeader>
                <ModalBody>
                  Your password has been reset,Please Check Your email
                </ModalBody>
                <ModalFooter>
                  <Button color="secondary" onClick={this.toggleAll}>
                    OK
                  </Button>
                </ModalFooter>
              </Modal>
            </ModalBody>
          </Modal>

          <Container>
            <Row className="justify-content-center">
              <Col md="8">
                {/* <CardGroup md="5"> */}
                {/* <Card className="d-md-down-none">
                    <img src={leftPicture} alt="" style={{ width: '100%' }} /> */}
                {/* <CardBody className="text-center">
                  </CardBody> */}
                {/* </Card> */}
                <Card
                  className="p-4"
                  style={{ width: "50%", margin: "auto", height: "365px" }}
                >
                  <CardBody>
                    <Form onSubmit={this.login}>
                      <Row className="logo">
                        <Col>
                          <img className="login" src={Logo} alt="" />
                        </Col>
                      </Row>
                      <Row style={{ marginBottom: "18px" }}>
                        <Col>
                          <Input
                            name="username_email"
                            placeholder="Email/username"
                            onChange={this.onChange}
                          />
                        </Col>
                      </Row>
                      <Row>
                        <Col>
                          <Input.Password
                            name="password"
                            placeholder="Password"
                            onChange={this.onChange}
                          />
                        </Col>
                      </Row>

                      <Row style={{ marginTop: "10px" }}>
                        <Col>
                          <Button className="btn-login" type="submit">
                            Login
                          </Button>
                        </Col>
                      </Row>
                      <Row>
                        <Col>
                          <Button
                            style={{ fontSize: "85%", marginTop: "1px" }}
                            className="forgot float-right"
                            color="link"
                            onClick={this.toggle}
                          >
                            Forgot Password?
                          </Button>
                        </Col>
                      </Row>
                    </Form>
                    <Row className="center-block">
                      <Alert
                        color="danger"
                        hidden={this.state.hidden}
                        style={{
                          marginTop: "3%",
                          fontSize: "72%",
                          marginBottom: "-7%"
                        }}
                      >
                        {this.state.message}
                      </Alert>
                    </Row>
                    <Row>
                      <Col className="colNST">
                        <h5
                          style={{
                            fontWeight: "lighter",
                            fontFamily: "inherit"
                          }}
                        >
                          NAS - LSP Login
                        </h5>
                      </Col>
                    </Row>
                    <Row
                      className="footerCopyright"
                      style={{ marginTop: "70px" }}
                    >
                      <Col>
                        Copyright &copy; 2019,
                        <a href="https://www.aplikasisertifikasi.com">NAS</a>.
                        All rights reserved
                      </Col>
                    </Row>
                  </CardBody>
                </Card>
                {/* </CardGroup> */}
              </Col>
            </Row>
            <NotificationContainer />
          </Container>
        </div>
      </LoadingOverlay>
    );
  }
}

export default withAlert(Login);
