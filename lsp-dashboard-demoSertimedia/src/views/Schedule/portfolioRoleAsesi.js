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
  NavLink
} from "reactstrap";
import classnames from "classnames";

import Axios from "axios";
import { multiLanguage } from "../../components/Language/getBahasa";
import ListPortofolioAsesi from "../../components/ListTables/ListPortofolioAsesi";
import { getData, path_assessments } from "../../components/config/config";
import ListPersyaratanDasar_roleasesi from "../../components/ListTables/ListPersyaratanDasar_roleasesi";
import ListPersyaratanUmum_roleasesi from "../../components/ListTables/ListPersyaratanUmum_roleasesi";

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
    const { assessment_id } = this.props.match.params;
    Axios(
      getData(
        "/me" + path_assessments + "/" + assessment_id + "/portfolios",
        "GET"
      )
    ).then(response => {
      this.setState({
        payload: response.data.data
      });
    });
  }

  render() {
    const { assessment_id, assessment_applicant_id } = this.props.match.params;
    const { applicant_id } = this.state.payload;
    return (
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
                  <ListPersyaratanDasar_roleasesi
                    IDAssessment={assessment_id}
                  />
                </TabPane>
              ) : (
                <TabPane className="animated fadeIn" tabId="2">
                  <ListPersyaratanUmum_roleasesi
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
          <Link to={"/dashboard"}>
            <button className="btn btn-danger" title={multiLanguage.back}>
              <i className="fa fa-chevron-left" /> {multiLanguage.back}
            </button>
          </Link>
        </CardFooter>
      </Card>
      // <div>
      // </div>
    );
  }
}

export default Portofolio;
