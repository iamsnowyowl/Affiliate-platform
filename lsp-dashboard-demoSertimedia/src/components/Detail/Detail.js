import React, { Component } from "react";
import {
  TabContent,
  TabPane,
  Nav,
  NavItem,
  NavLink,
  Card,
  CardFooter,
  Button,
  Row,
  Col
} from "reactstrap";
import { Link } from "react-router-dom";
import Axios from "axios";
import classnames from "classnames";
import { Divider, Skeleton, Modal } from "antd";
import "../../css/dataRecord.css";
import {
  path_assessments,
  path_schemaViews,
  getData,
  formatDate
} from "../../components/config/config";
import DetailAsesor from "./DetailAsesor";
import DetailAdmin from "./DetailAdmin";
import DetailAsesi from "./DetailAsesi";
import DetailPleno from "./DetailPleno";
import { multiLanguage } from "../Language/getBahasa";

export default class Detail extends Component {
  constructor(props) {
    super(props);

    this.state = {
      data: {
        user_id: "",
        sub_schema_number: "",
        pleno_date: ""
      },
      user_id: "",
      activeTab: "",
      payloadDetail: [],
      payloadSchema: [],
      detail: true,
      assessmentID: "",
      tabs: "",
      type: "",
      loading: true
    };
  }

  toggle = tab => {
    if (this.state.activeTab !== tab) {
      this.setState({
        activeTab: tab
      });
      this.setState({ tabs: tab });
    }
  };

  Get(options, response) {
    Axios(options).then(res => {
      this.setState({
        [response]: res.data.data,
        loading: false
      });
    });
  }

  errorTrial = () => {
    Modal.error({
      title: "Pesan Error",
      content:
        "Masa trial anda telah habis,Harap menghubungi Admin NAS untuk info lebih lanjut",
      onOk() {
        localStorage.clear();
        window.location.replace("/login");
      }
    });
  };

  componentDidMount() {
    const activeRun = this.props.location.state;
    if (activeRun === undefined) {
      this.setState({
        activeTab: "1"
      });
    } else {
      this.setState({
        activeTab: activeRun.runs
      });
    }

    if (this.state.type === undefined) {
      this.setState({
        type: "NOT_APL"
      });
    }

    const id_assessment = this.props.match.params.assessment_id;
    this.setState({ assessmentID: id_assessment });
    const pathAssessment = path_assessments + "/" + id_assessment;
    const pathSchema = "/public" + path_schemaViews;
    const method = "GET";
    Axios(getData(pathAssessment, method))
      .then(response => {
        this.setState({
          payloadDetail: response.data.data,
          loading: false
        });
      })
      .catch(error => {
        console.log("error bos", error.response);
        if (error.response.status === 401) {
          localStorage.clear();
          window.location.replace("/login");
        } else if (error.response.status === 419) {
          this.errorTrial();
        }
      });
    this.Get(getData(pathSchema, method), "payloadSchema");
  }

  render() {
    const { activeTab, payloadDetail, loading } = this.state;
    return (
      <div className="animated fadeIn">
        <Card>
          <Nav tabs>
            <NavItem>
              <NavLink
                className={classnames({ aktif: activeTab === "1" })}
                onClick={() => {
                  this.toggle("1");
                }}
              >
                {multiLanguage.assessors}
              </NavLink>
            </NavItem>
            <NavItem>
              <NavLink
                className={classnames({ aktif: activeTab === "2" })}
                onClick={() => {
                  this.toggle("2");
                }}
              >
                Peserta
              </NavLink>
            </NavItem>
            <NavItem>
              <NavLink
                className={classnames({ aktif: activeTab === "3" })}
                onClick={() => {
                  this.toggle("3");
                }}
              >
                Admin
              </NavLink>
            </NavItem>
            <NavItem>
              <NavLink
                className={classnames({
                  aktif: activeTab === "4"
                })}
                onClick={() => {
                  this.toggle("4");
                }}
              >
                Pleno
              </NavLink>
              {/* )} */}
            </NavItem>
          </Nav>

          <TabContent activeTab={activeTab}>
            <div style={{ marginLeft: "15px", marginRight: "15px" }}>
              <Divider>Detail {multiLanguage.Assessment}</Divider>
              <Skeleton loading={loading}>
                <Row style={{ marginBottom: "5px" }}>
                  <Col md="2">{multiLanguage.assessmentName}</Col>
                  <Col>: {payloadDetail.title}</Col>
                </Row>
                <Row style={{ marginBottom: "5px" }}>
                  <Col md="2">{multiLanguage.schemaName}</Col>
                  <Col>: {payloadDetail.schema_label}</Col>
                </Row>
                <Row style={{ marginBottom: "5px" }}>
                  <Col md="2">{multiLanguage.location}</Col>
                  <Col>: {payloadDetail.address}</Col>
                </Row>
                <Row style={{ marginBottom: "5px" }}>
                  <Col md="2">{multiLanguage.date}</Col>
                  <Col>: {formatDate(payloadDetail.start_date)}</Col>
                </Row>
              </Skeleton>
              <Divider />
            </div>
            {activeTab === "1" ? (
              <TabPane className="animated fadeIn" tabId="1">
                <DetailAsesor
                  params={this.props.match.params}
                  run={1}
                  payloadDetail={payloadDetail}
                />
              </TabPane>
            ) : activeTab === "2" ? (
              <TabPane className="animated fadeIn" tabId="2">
                <DetailAsesi
                  params={this.props.match.params}
                  run={2}
                  payloadDetail={payloadDetail}
                />
              </TabPane>
            ) : activeTab === "3" ? (
              <TabPane className="animated fadeIn" tabId="3">
                <DetailAdmin
                  params={this.props.match.params}
                  run={3}
                  payloadDetail={payloadDetail}
                />
              </TabPane>
            ) : (
              <TabPane className="animated fadeIn" tabId="4">
                <DetailPleno
                  params={this.props.match.params}
                  run={4}
                  payloadDetail={payloadDetail}
                />
              </TabPane>
            )}
          </TabContent>
          <CardFooter>
            <Link to={path_assessments + "/list"}>
              <Button type="submit" size="md" color="danger">
                <i className="fa fa-chevron-left" /> {multiLanguage.back}
              </Button>
            </Link>
          </CardFooter>
        </Card>
      </div>
    );
  }
}
