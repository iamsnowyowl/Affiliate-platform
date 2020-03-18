import React, { Component } from "react";
import { Redirect } from "react-router-dom";
import {
  DropdownItem,
  DropdownMenu,
  DropdownToggle,
  Nav,
  Modal,
  ModalHeader,
  ModalBody,
  ModalFooter,
  Button,
  Row,
  Col
} from "reactstrap";
import PropTypes from "prop-types";
import {
  NotificationContainer,
  NotificationManager
} from "react-notifications";
import {
  AppHeaderDropdown,
  AppNavbarBrand,
  AppSidebarToggler
} from "@coreui/react";

import { baseUrl, path_notif, Brand_LSP } from "../../components/config/config";
import "react-notifications/lib/notifications.css";
import "../../css/Dashboard.css";

import firebase from "firebase/app";
import "firebase/messaging";
import axios from "axios";

import { Digest } from "../Helpers/digest";
import { multiLanguage } from "../../components/Language/getBahasa";

import "react-notifications/lib/notifications.css";
import "../../css/Dashboard.css";

if (!firebase.app.length) {
  firebase.initializeApp({});
}

const propTypes = {
  children: PropTypes.node
};

const defaultProps = {};

class DefaultHeader extends Component {
  constructor(props) {
    super(props);
    this.state = {
      logged_in: false,
      notifications: false,
      badge: false,
      sumNotification: 0,
      hide: false,
      modal: false,
      backdrop: true,
      notif: "Notification",
      titleNotif: "Notification",
      detailAssessor: "",
      detailStatus: "",
      detailSchedule_id: "",
      bellNotif: false,
      payloadNotif: [],
      detailNotif: "",
      bahasa: "en",
      user_id: JSON.parse(localStorage.getItem("userdata"))
    };
  }

  componentDidMount() {
    const auth = Digest(path_notif, "GET");
    const options = {
      method: auth.method,
      headers: {
        Authorization: auth.digest,
        "X-Lsp-Date": auth.date,
        "Content-Type": "application/json"
      },
      url: baseUrl + path_notif + "?sort=is_read"
    };
    axios(options).then(res => {
      this.setState({ payloadNotif: res.data.data });
    });
    // notification in website page
    const messaging = firebase.messaging();
    messaging.onMessage(payload => {
      const message = payload.notification.body;
      const title = payload.notification.title;
      const timeOut = 2000;
      this.setState({
        notif: "New Message",
        titleNotif: title,
        bellNotif: !this.state.bellNotif,
        badge: true,
        sumNotification: this.state.sumNotification + 1
      });

      // status notif from server: ACCEPTED ande DECLINED
      switch (payload.notification.click_action) {
        case "LSPACSNTFOFR":
          NotificationManager.success(message, title, timeOut);
          break;

        case "LSPACSNTFDEF":
          NotificationManager.info(message, title, timeOut);
          break;

        default:
          break;
      }
    });
  }

  toggle = event => {
    const notification_id = event.target.value;
    const auth = Digest(path_notif + "/" + notification_id, "GET");
    const options = {
      method: auth.method,
      headers: {
        Authorization: auth.digest,
        "X-Lsp-Date": auth.date,
        "Content-Type": "application/json"
      },
      url: baseUrl + path_notif + "/" + notification_id
    };
    axios(options).then(res => {
      const json = JSON.parse(res.data.data.data);
      this.setState({
        detailAssessor: json.accessor_id,
        detailSchedule_id: json.assessment_id,
        detailStatus: json.last_state_assessor
      });
    });
    this.setState({
      modal: !this.state.modal,
      backdrop: "static"
    });
  };

  toggleExit = () => {
    const auth = Digest(path_notif, "GET");
    const options = {
      method: auth.method,
      headers: {
        Authorization: auth.digest,
        "X-Lsp-Date": auth.date,
        "Content-Type": "application/json"
      },
      url: baseUrl + path_notif + "?sort=is_read"
    };
    axios(options).then(res => {
      this.setState({ payloadNotif: res.data.data });
    });
    this.setState({
      modal: !this.state.modal,
      bellNotif: false,
      badge: false,
      sumNotification: 0,
      notif: "Notification"
    });
  };

  hideBadge = () => {
    // const value = this.state.hide;
  };

  logout = () => {
    localStorage.removeItem("role");
    localStorage.clear();
    localStorage.removeItem("role");
    this.setState({ logged_in: true });
  };

  render() {
    const { title, Logo } = Brand_LSP("demo");
    if (this.state.logged_in) {
      return <Redirect to={"/login"} />;
    }
    var user = JSON.parse(localStorage.getItem("userdata"));
    const externalCloseBtn = (
      <button
        className="close"
        style={{ position: "absolute", top: "15px", right: "15px" }}
        onClick={this.toggle}
      >
        &times;
      </button>
    );
    return (
      <React.Fragment>
        <Modal
          isOpen={this.state.modal}
          toggle={this.toggle}
          className={this.props.className}
          backdrop={this.state.backdrop}
          external={externalCloseBtn}
        >
          <ModalHeader>Detail Notification</ModalHeader>
          <ModalBody>
            <Row>
              <Col xs="4">Accessor</Col>
              <Col xs="6">: {this.state.detailAssessor}</Col>
            </Row>
            <Row>
              <Col xs="4"> Status</Col>
              <Col xs="6">: {this.state.detailStatus}</Col>
            </Row>
            <Row>
              <Col xs="4"> Assessment</Col>
              <Col xs="6">: {this.state.detailSchedule_id}</Col>
            </Row>
          </ModalBody>
          <ModalFooter>
            <Button color="secondary" onClick={this.toggleExit}>
              Cancel
            </Button>
          </ModalFooter>
        </Modal>
        <AppSidebarToggler className="d-lg-none" display="md" mobile />
        {/* <AppSidebarToggler className="d-md-down-none" display="lg" /> */}
        <AppNavbarBrand
          full={{ src: Logo, width: 150, height: 60, alt: "LSP Logo" }}
          minimized={{ src: Logo, width: 150, height: 60, alt: "LSP Logo" }}
        />

        <NotificationContainer />

        <Nav className="ml-auto" navbar>
          <AppHeaderDropdown direction="down">
            {`Hai, ${user.first_name} ${user.last_name}`}
            <DropdownToggle
              nav
              className="badgeTooltip"
              onClick={this.hideBadge}
            >
              <img
                src={baseUrl + user.picture + "?width=56&height=56"}
                className="img-avatar"
                alt="admin"
              />
              {/* <span className="tooltiptext"></span> */}
            </DropdownToggle>

            <DropdownMenu right style={{ right: "auto" }}>
              <DropdownItem header tag="div" className="text-center">
                {user.role_name}
              </DropdownItem>
              {/* {localStorage.getItem("bahasa") === "id" ||
              localStorage.getItem("bahasa") === null ? (
                <DropdownItem onClick={this.props.profile.bind(this, "en")}>
                  <span className="flag-icon flag-icon-gb" /> Ganti Ke English
                </DropdownItem>
              ) : (
                <DropdownItem onClick={this.props.profile.bind(this, "id")}>
                  {" "}
                  <span className="flag-icon flag-icon-id" /> Change to Bahasa
                </DropdownItem>
              )} */}
              <DropdownItem className="fa fa-lock" onClick={this.logout}>
                {" "}
                {multiLanguage.logout}
              </DropdownItem>
            </DropdownMenu>
          </AppHeaderDropdown>
        </Nav>
      </React.Fragment>
    );
  }
}
DefaultHeader.propTypes = propTypes;
DefaultHeader.defaultProps = defaultProps;

export default DefaultHeader;
