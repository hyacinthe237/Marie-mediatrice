import Vue from 'vue'
import Router from 'vue-router'
import HomeView from '../views/home/home'
import AccountView from '../views/account/account'
import FinancesView from '../views/finances/finances'
import EventsView from '../views/events/events'
import MembersView from '../views/members/members'
import MemberView from '../views/members/member'
import PinView from '../views/account/pin'
import FundsView from '../views/funds/funds'
import FundView from '../views/funds/fund'
import ExpensesView from '../views/funds/expenses'
import AboutView from '../views/about/about'

Vue.use(Router)
export default new Router({
    mode: 'history',
    routes: [
        { path: '/', name: 'home', component: HomeView },
        { path: '/account', name: 'account', component: AccountView },
        { path: '/finances', name: 'userfinances', component: FinancesView },
        { path: '/events', name: 'events', component: EventsView },
        { path: '/members', name: 'members', component: MembersView },
        { path: '/members/:id', name: 'member', component: MemberView },
        { path: '/pin', name: 'pin', component: PinView },
        { path: '/funds', name: 'funds', component: FundsView },
        { path: '/funds/:id', name: 'fund', component: FundView },
        { path: '/expenses', name: 'expenses', component: ExpensesView },
        { path: '/about', name: 'about', component: AboutView },
    ]
})
