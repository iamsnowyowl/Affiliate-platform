import React, { Component, Suspense } from 'react';
import { Redirect, Route, Switch } from 'react-router-dom';
import { Container } from 'reactstrap';

import {
  AppAside,
  AppFooter,
  AppHeader,
  AppSidebar,
  AppSidebarFooter,
  AppSidebarForm,
  AppSidebarHeader,
  AppSidebarNav,
  // AppBreadcrumb,
  // AppSidebarMinimizer
} from '@coreui/react';

import { Permission } from '../../_nav';
import routes from '../../routes';
import DefaultAside from './DefaultAside';
import DefaultFooter from './DefaultFooter';
import DefaultHeader from './DefaultHeader';
import '../../css/Dashboard.css';
import '../../css/Navbar.css';
import '../../css/Button.css';
import { getRole } from '../../components/config/config';

class DefaultLayout extends Component {
  constructor(props) {
    super(props);
    this.state = {
      logged_in: true,
      bahasa: '',
      permission: []
    };
  }

  profile(codeBahasa) {
    localStorage.setItem('bahasa', codeBahasa);
    window.location.reload();
  }

  loading = () => (
    <div className="animated fadeIn pt-1 text-center">Loading...</div>
  );

  render() {
    if (localStorage.length === 0) {
      return <Redirect to={'/login'} />;
    }
    return (
      <div className="app">
        <AppHeader fixed>
          <Suspense fallback={this.loading()}>
            <DefaultHeader
              profile={this.profile.bind(this)}
              bahasa={this.state.bahasa}
            />
          </Suspense>
        </AppHeader>
        <div className="app-body ">
          <AppSidebar fixed display="lg" minimized={false}>
            <AppSidebarHeader />
            <AppSidebarForm />
            <Suspense>
              <AppSidebarNav
                navConfig={
                  Permission(localStorage.getItem('bahasa'), getRole())
                  // this.state.rolePermission === 'dev' ? (
                  //   this.state.bahasa === 'en' ? (english) : (indo)
                  // ) : (
                  //   english
                  // )
                }
                {...this.props}
              />
              {/* <AppSidebarMinimizer /> */}
            </Suspense>
            <AppSidebarFooter />
          </AppSidebar>
          <main className="main">
            {/* <AppBreadcrumb className="BreadCrumb" appRoutes={routes} /> */}
            <p />
            <Container fluid>
              <Switch>
                {routes.map((route, idx) => {
                  return route.component ? (
                    <Route
                      key={idx}
                      path={route.path}
                      exact={route.exact}
                      name={route.name}
                      render={props => <route.component {...props} />}
                    />
                  ) : null;
                })}
                <Redirect from="/" to="/dashboard" />
              </Switch>
            </Container>
          </main>
          <AppAside fixed hidden>
            <DefaultAside />
          </AppAside>
        </div>
        <AppFooter>
          <DefaultFooter />
        </AppFooter>
      </div>
    );
  }
}

export default DefaultLayout;
