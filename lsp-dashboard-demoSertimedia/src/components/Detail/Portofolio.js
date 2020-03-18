import React, { Component } from "react";
import { Link } from "react-router-dom";
import {
  Card,
  CardHeader,
  CardBody,
  CardFooter,
  Row,
  Col,
  TabContent,
  TabPane,
  Nav,
  NavItem,
  NavLink,
  Button
} from "reactstrap";
import classnames from "classnames";
import { Modal } from "antd";

import ListPortofolioAsesi from "../ListTables/ListPortofolioAsesi";
import {
  path_assessments,
  getData,
  path_applicant,
  getRole
} from "../config/config";
import { multiLanguage } from "../Language/getBahasa";
import Axios from "axios";
import ListPersyaratanUmum from "../ListTables/ListPersyaratanUmum";
import ListPersyaratanDasar from "../ListTables/ListPersyaratanDasar";
class Portofolio extends Component {
  constructor(props) {
    super(props);

    this.state = {
      activeTab: "1",
      tabs: "",
      payload: []
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

  componentDidMount() {
    const { assessment_id, assessment_applicant_id } = this.props.match.params;
    Axios(
      getData(
        path_assessments +
          "/" +
          assessment_id +
          path_applicant +
          "/" +
          assessment_applicant_id,
        "GET"
      )
    )
      .then(response => {
        this.setState({
          payload: response.data.data
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

  goBack = () => {
    console.log(this.props.history);
    this.props.history.goBack();
  };

  render() {
    const { assessment_id, assessment_applicant_id } = this.props.match.params;
    const { applicant_id } = this.state.payload;
    const acs_id = JSON.parse(localStorage.getItem("userdata")).user_id;
    const role = getRole();

    return (
      <div>
        <Card>
          <CardHeader>
            <Row>
              <Col md="5">
                <h5
                  style={{
                    textDecoration: "underline",
                    color: "navy"
                  }}
                >
                  Berkas Asesi
                </h5>
              </Col>
            </Row>
          </CardHeader>
          {applicant_id !== "0" ? (
            <CardBody>
              <Nav tabs>
                <NavItem />
                <NavItem>
                  <NavLink
                    className={classnames({
                      aktif: this.state.activeTab === "1"
                    })}
                    onClick={() => {
                      this.toggle("1");
                    }}
                  >
                    Persyaratan Dasar
                  </NavLink>
                </NavItem>
                <NavItem>
                  <NavLink
                    className={classnames({
                      aktif: this.state.activeTab === "2"
                    })}
                    onClick={() => {
                      this.toggle("2");
                    }}
                  >
                    Persyaratan Umum
                  </NavLink>
                </NavItem>
              </Nav>

              <TabContent activeTab={this.state.activeTab}>
                {this.state.activeTab === "1" ? (
                  <TabPane className="animated fadeIn" tabId="1">
                    <ListPersyaratanDasar
                      assessment_applicant_id={assessment_applicant_id}
                      assessment_id={assessment_id}
                    />
                  </TabPane>
                ) : (
                  <TabPane className="animated fadeIn" tabId="2">
                    <ListPersyaratanUmum
                      applicant_id={applicant_id}
                      assessment_id={assessment_id}
                      assessment_applicant_id={assessment_applicant_id}
                    />
                  </TabPane>
                )}
              </TabContent>
            </CardBody>
          ) : (
            <CardBody>
              <ListPortofolioAsesi
                assessment_applicant_id={assessment_applicant_id}
                assessment_id={assessment_id}
              />
            </CardBody>
          )}
          <CardFooter>
            {role === "ACS" ? (
              <Link
                to={
                  path_assessments + "/" + assessment_id + "/assign/" + acs_id
                }
              >
                <button className="btn btn-danger" title={multiLanguage.back}>
                  <i className="fa fa-chevron-left" /> {multiLanguage.back}
                </button>
              </Link>
            ) : (
              <Button
                className="btn btn-danger"
                title={multiLanguage.back}
                onClick={this.goBack}
              >
                <i className="fa fa-chevron-left" /> {multiLanguage.back}
              </Button>
            )}
          </CardFooter>
        </Card>
      </div>
    );
  }
}

export default Portofolio;
