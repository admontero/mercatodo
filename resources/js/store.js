import { createStore } from 'vuex';

let cart = window.localStorage.getItem('cart');
let cartCount = window.localStorage.getItem('cartCount');

export default createStore({
    state () {
      return {
        cart: cart ? JSON.parse(cart) : [],
        cartCount: cartCount ? parseInt(cartCount) : 0,
      }
    },
    mutations: {
        addToCart(state, product) {
            let productInCartIndex = state.cart.findIndex(item => item.slug === product.slug)

            if (product.stock < 1) {
                return
            }

            if (productInCartIndex === -1) {
                product.quantity = 1
                state.cart.push(product)
                state.cartCount++
                this.commit('saveCart');

                return
            }

            if (state.cart[productInCartIndex].quantity < product.stock) {
                state.cart[productInCartIndex].quantity++
                this.commit('saveCart');
            }
        },
        removeFromCart(state, product) {
            let productInCartIndex = state.cart.indexOf(product);

            if (productInCartIndex > -1) {
                state.cartCount--
                state.cart.splice(productInCartIndex, 1)
                this.commit('saveCart');
            }
        },
        incrementFromCart(state, product) {
            let productInCartIndex = state.cart.indexOf(product)

            if (productInCartIndex == -1) {
                return
            }

            if (state.cart[productInCartIndex].quantity < product.stock) {
                state.cart[productInCartIndex].quantity++
                this.commit('saveCart');
            }
        },
        decrementFromCart(state, product) {
            let productInCartIndex = state.cart.indexOf(product);

            if (productInCartIndex == -1) {
                return
            }

            if (state.cart[productInCartIndex].quantity == 1) {
                state.cartCount--
                state.cart.splice(productInCartIndex, 1)
                this.commit('saveCart');

                return
            }

            state.cart[productInCartIndex].quantity--
        },
        saveCart(state) {
            window.localStorage.setItem('cart', JSON.stringify(state.cart))
            window.localStorage.setItem('cartCount', state.cartCount)
        }
    },
})
