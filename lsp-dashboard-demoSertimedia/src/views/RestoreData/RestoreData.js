import React, { Component } from "react";
import {
  Card,
  CardHeader,
  CardBody,
  TabContent,
  TabPane,
  Nav,
  NavItem,
  NavLink
} from "reactstrap";
import classnames from "classnames";

import { multiLanguage } from "../../components/Language/getBahasa";
import HistoryAssessment from "../../components/HistoryLog/HistoryAssessment";
import HistoryUsers from "../../components/HistoryLog/HistoryUsers";

class RestoreData extends Component {
  constructor(props) {
    super(props);
    this.state = {
      activeTab: "1",
      tabs: ""
    };
  }

  toggle = tab => {
    if (this.state.activeTab !== tab) {
      this.setState({
        activeTab: tab,
        tabs: tab
      });
    }
  };

  goback = () => {
    this.props.history.goback();
  };

  render() {
    return (
      <div className="animated fadeIn">
        <Card>
          <CardHeader>
            <h5
              style={{
                textDecoration: "underline",
                color: "navy"
              }}
            >
              Data Restore
            </h5>
          </CardHeader>
          <CardBody>
            <Nav tabs>
              <NavItem>
                <NavLink
                  className={classnames({
                    aktif: this.state.activeTab === "1"
                  })}
                  onClick={() => {
                    this.toggle("1");
                  }}
                >
                  Data {multiLanguage.Assessment}
                </NavLink>
              </NavItem>
              <NavItem>
                <NavLink
                  className={classnames({
                    aktif: this.state.activeTab === "1"
                  })}
                  onClick={() => {
                    this.toggle("2");
                  }}
                >
                  Data {multiLanguage.user}
                </NavLink>
              </NavItem>
            </Nav>

            <TabContent activeTab={this.state.activeTab}>
              {this.state.activeTab === "1" ? (
                <TabPane className="animated fadeIn" tabId="1">
                  <HistoryAssessment />
                </TabPane>
              ) : (
                <TabPane className="animated fadeIn" tabId="2">
                  <HistoryUsers />
                </TabPane>
              )}
            </TabContent>
          </CardBody>
        </Card>
      </div>
    );
  }
}

export default RestoreData;
